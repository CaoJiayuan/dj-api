<?php
/**
 * TruckRepository.php
 * Date: 16/5/17
 * Time: 上午10:59
 */

namespace App\Repositories;

use Api\StarterKit\Utils\ApiResponse;
use App\Entity\ChauffeurJourneyPayRule;
use App\Entity\ChauffeurPayRule;
use App\Entity\City;
use App\Entity\OrderCancel;
use App\Entity\Tour;
use App\Entity\Trip;
use App\Entity\TripOrder;
use App\Traits\Certificated;
use App\Traits\Payment;
use App\Traits\PushMessage;
use App\Traits\TripAndOrders;
use App\Transformers\LocalTripItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use App\Entity\Account;

class ChauffeurRepository extends Repository
{

  use TripAndOrders, ApiResponse, PushMessage, Payment;

  /**
   * @var Trip
   */
  protected $model;


  /**
   * @param $lng
   * @param $lat
   * @param int $page
   * @param int $limit
   * @return Collection|static[]
   */
  public function getList($lng, $lat, $page = 1, $limit = 20)
  {
    $builder = $this->model->leftJoin('accounts', 'accounts.id', '=', 'trips.account_id');

    $builder->select([
      'trips.id',
      'trips.time',
      'trips.created_at',
      'trips.start',
      'trips.destination',
      'trips.latitude',
      'trips.longitude',
      'trips.latitude2',
      'trips.longitude2',
      'trips.trip_fee',
      'trips.type',
      'trips.population',
      'accounts.nickname',
      'accounts.username',
      'accounts.avatar',
      'accounts.sex',
      \DB::raw("getDistance($lng,$lat,longitude,latitude) as dis"),
      \DB::raw('CURRENT_TIMESTAMP - tr_trips.created_at as t'),
    ]);

    $builder->where('status', '=', TRIP_PUBLISHED);
    $builder->where('type', '=', TYPE_CHAUFFEUR_JOURNEY);

    $builder->orWhere(function ($query) {
      $time = Carbon::now()->addMinutes(-LOCAL_TRIP_TIME_LIMIT);
      /** @var Builder $query */
      $query->where('type', '=', TYPE_CHAUFFEUR);
      $query->where('status', '=', TRIP_PUBLISHED);
      $query->whereRaw("tr_trips.created_at > '$time'");
    });

    $builder->orderBy('dis')->orderBy('created_at', 'desc');

    $builder->forPage($page, $limit);

    $col = $builder->get();

    foreach ($col as $key => $item) {
      if ($item['type'] == TYPE_CHAUFFEUR) {
        $col[$key]['time'] = $item['created_at'];
      }
    }

    return $col;
  }

  public function publish($data)
  {
    return get_formatted($this->publishTrip($data), LocalTripItem::class);
  }

  public function accept($id)
  {
    if (!$trip = Trip::find($id)) {
      return $this->respondNotFound('行程不存在');
    }
    if ($trip->status != TRIP_PUBLISHED) {
      return $this->respondForbidden('未能抢到该行程:(');
    }

    $this->matchTypeOr($trip, [TYPE_CHAUFFEUR, TYPE_CHAUFFEUR_JOURNEY]);

    if ($trip->type == TYPE_CHAUFFEUR) {
      if (time() - $trip->created_at > LOCAL_TRIP_TIME_LIMIT * 60) {
        return $this->respondForbidden('酒后代驾行程已经超时作废');
      }
    }

    return \DB::transaction(function () use ($trip) {
      $user = $this->getUser();
      $trip->status = TRIP_ACCEPTED;

      TripOrder::create([
        'driver_id' => $user->id,
        'passenger_id' => $trip->account_id,
        'trip_id' => $trip->id,
        'amount' => $trip->trip_fee,
      ]);
      $user->receivable = false;
      $user->working = false;
      $user->save();
      $pushType = $trip->type == TYPE_CHAUFFEUR ? PUSH_CHA_ACCEPTED : PUSH_CHA_JOU_ACCEPTED;

      $trip->save();

      $type = $trip->getReadableType();

      $this->transmissionToAccounts($trip->account, "你的{$type}行程被接受", $pushType,
        ['id' => $trip->id, 'account_id' => $user->id]);

      return $trip;
    });

  }

  public function cancel($id, $reason = '')
  {
    $trip = Trip::find($id);

    if (!$trip) {
      return $this->respondNotFound('行程不存在');
    }
    $this->matchTypeOr($trip, [TYPE_CHAUFFEUR, TYPE_CHAUFFEUR_JOURNEY]);

    if ($trip->status > TRIP_IN_POSITION) {
      if ($trip->status = TRIP_CANCELED) {
        return $this->respondForbidden('行程已经被取消');
      }

      return $this->respondForbidden('乘客已经上车,不能取消当前行程');
    }

    return \DB::transaction(function () use ($trip, $reason) {
      $user = $this->getUser();
      $trip->status = TRIP_CANCELED;

      foreach ($trip->orders as $order) {
        $punishment = $order->amount * CANCEL_PUNISHMENT_RATE;
        $cancelAmount = $user->orderCanceledToday($trip->type, ROLE_TYPE)->count();
        if (env('IS_FINE')) {
          if ($cancelAmount >= 2) {
            if ($trip->type == TYPE_CHAUFFEUR_JOURNEY) {
              if ($trip->time - time() < 10 * 60) {
                $this->changeBalance($user, -$punishment, '取消行程扣除', false);
              }
            }
          }
        }
//          $this->changeBalance($user, -$punishment, '取消行程扣除', false);
//        } else {
//          if ($trip->type == TYPE_CHAUFFEUR_JOURNEY) {
//            if ($trip->time - time() < 10 * 60) {
//              $this->changeBalance($user, -$punishment, '取消行程扣除', false);
//            }
//          }
//        }

        $order->status = ORDER_CANCELED;
        $order->cancel_reason = $reason;
        $order->save();
        OrderCancel::create([
          'account_id' => $user->id,
          'role' => ROLE_TYPE,
          'type' => $trip->type,
          'order_id' => $order->id,
        ]);
        $pushType = ROLE_TYPE ?
          ($trip->type == TYPE_CHAUFFEUR ? PUSH_CHA_DIR_CANCELED : PUSH_CHA_JOU_DIR_CANCELED)
          : ($trip->type == TYPE_CHAUFFEUR ? PUSH_CHA_PAS_CANCELED : PUSH_CHA_JOU_PAS_CANCELED);

        $target = ROLE_TYPE ? $order->passenger : $order->driver;
        $canceller = ROLE_TYPE ? '司机' : '乘客';

        //补偿
        if (!env('IS_FINE')) $punishment = 0;
        $id = ROLE_TYPE ? $order->passenger_id : $order->driver_id;
        Account::whereId($id)->increment('balance', $punishment);


        $this->transmissionToAccounts($target, "行程被{$canceller}取消", $pushType, ['id' => $trip->id]);
      }
      $trip->save();

      return $trip;
    });
  }

  public function active($id)
  {
    if (!$trip = Trip::find($id)) {
      return $this->respondNotFound('行程不存在');
    }
    $this->matchTypeOr($trip, [TYPE_CHAUFFEUR, TYPE_CHAUFFEUR_JOURNEY]);

    $user = $this->getUser();
    if ($driver = $trip->driver) {
      if ($driver->id != $user->id) {
        return $this->respondForbidden('当前订单不是你的行程订单');
      }
    }

    if ($trip->status < TRIP_IN_POSITION) {

      if ($trip->type == TYPE_CHAUFFEUR) {
        return $this->respondUnprocessable('你未到达乘客位置');
      } else {
        return $this->respondUnprocessable('行程尚未预付款');
      }
    }

    if ($trip->status > TRIP_IN_POSITION) {
      return $this->respondForbidden('行程已进行或已取消');
    }

    return \DB::transaction(function () use ($trip) {

      $trip->status = TRIP_ACTIVE;
      $trip->start_at = Carbon::now();
      $trip->save();
      $pushType = $trip->type == TYPE_CHAUFFEUR ? PUSH_CHA_ACTIVE : PUSH_CHA_JOU_ACTIVE;
      $this->transmissionToAccounts($trip->account, '你已上车,行程开始', $pushType, ['id' => $trip->id]);

      return $trip;
    });
  }

  public function finish($id, $distance)
  {
    if (!$trip = Trip::find($id)) {
      return $this->respondNotFound('行程不存在');
    }
    $this->matchTypeOr($trip, [TYPE_CHAUFFEUR, TYPE_CHAUFFEUR_JOURNEY]);

    $user = $this->getUser();
    if ($driver = $trip->driver) {
      if ($driver->id != $user->id) {
        return $this->respondForbidden('当前订单不是你的行程订单');
      }
    }

    if ($trip->status < TRIP_ACTIVE) {
      return $this->respondForbidden('行程未开始');
    }
    if ($trip->status > TRIP_ACTIVE) {
      return $this->respondForbidden('行程已完成或已取消');
    }

    return \DB::transaction(function () use ($trip, $distance) {

      $order = $trip->order;

      $city = $trip->city;

      if (!$city) {
        $city = new City();
        $city->id = 0;
      }
      $trip->status = TRIP_FINISHED;
      if (!$distance) {
        $distance = $trip->distance / 1000;
      }
      $order->distance = $distance * 1000;

      if ($trip->type == TYPE_CHAUFFEUR) {
        $pushType = PUSH_CHA_FINISHED;
        /** @var ChauffeurPayRule $rule */
        $rule = $city->chauffeurPayRule->first();
        $diff1 = $distance - $rule->limit;
        if ($diff1 <= 0) {
          $diff1 = 0;
        } else {
          $diff1 = ceil($diff1 / $rule->limit2);
        }
        $order->distance = $distance * 1000;
        $order->start_fee = $rule->init_price;
        $order->distance_fee = $diff1 * $rule->distance_price;
        $order->amount = $order->start_fee + $order->distance_fee;
      } else {
        $pushType = PUSH_CHA_JOU_FINISHED;

        /** @var ChauffeurJourneyPayRule $r */
        $r = $city->chauffeurJourneyPayRule->first();

        if ($distance > $r->limit) {
          $pay = ($distance / 100) * $r->more_price;
          $back = ($distance / 100) * $r->more_price_back;
        } else {
          $pay = ($distance / 100) * $r->less_price;
          $back = ($distance / 100) * $r->less_price_back;
        }

        $order->distance = $distance * 1000;
        $order->distance_fee = round($pay);
        $order->back_fee = round($back);
        $order->amount = $order->distance_fee + $order->back_fee;
      }
      $order->save();
      $trip->save();
      $this->transmissionToAccounts($trip->account, '行程已结束', $pushType, ['id' => $trip->id]);

      return $trip;
    });
  }

  /**
   * @return Model
   */
  public function getModel()
  {
    return Trip::class;
  }
}