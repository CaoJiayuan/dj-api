<?php
/**
 * Journey.php
 * Date: 16/5/16
 * Time: 下午4:39
 */

namespace App\Repositories;


use Api\StarterKit\Utils\ApiResponse;
use App\Entity\City;
use App\Entity\JourneyOrder;
use App\Entity\JourneyPayRule;
use App\Entity\OrderCancel;
use App\Entity\Tour;
use App\Entity\Trip;
use App\Entity\TripOrder;
use App\Traits\Payment;
use App\Traits\PushMessage;
use App\Traits\TripAndOrders;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Entity\Account;

class JourneyRepository extends Repository
{

  use ApiResponse, PushMessage, Payment, TripAndOrders;

  /**
   * @var Trip
   */
  protected $model;

  public function getList($lng, $lat, $role = ROLE_DRIVER, $page = 1, $size = 20, $city = null, $recommend = 0)
  {
    $builder = $this->model->published()->with('account');
    $builder->where('trips.role', '=', $role);
    $lng2 = 0;
    $lat2 = 0;
    if ($role == 0) {
      $role = 1;
    } elseif ($role == 1) {
      $role = 0;
    }
    $user = $this->getUser();
    if ($unfinished = $user->unfinishedTrip(null, $role)) {
      $recommend = 1;
      if ($unfinished->type == TYPE_JOURNEY || $unfinished->type == TYPE_JOURNEY_ONLY || $unfinished->type == TYPE_JOURNEY_SPECIAL) {
        if (!$unfinished->pool && $unfinished->account_id != $user->id) {
          return collect();
        }
        if ($unfinished->type != TYPE_JOURNEY) {
          $builder->where('trips.car_model', '=', $unfinished->car_model);
        }
        $builder->having('trips.time', '<=', date('Y-m-d H:i:s', strtotime('+5 hour', $unfinished->time)));
        $builder->having('trips.time', '>=', date('Y-m-d H:i:s', strtotime('-5 hour', $unfinished->time)));
        $builder->where('trips.type', '=', $unfinished->type);
        $builder->orderBy('trips.time');

        $lng = $unfinished->longitude;
        $lat = $unfinished->latitude;
        $lng2 = $unfinished->longitude2;
        $lat2 = $unfinished->latitude2;
        if ($role == 0) {
          $builder->where('trips.rest_sets', '>=', $unfinished->population);
        } else {
          $builder->where('trips.population', '<=', $unfinished->rest_sets);
        }
      }
    } else {
      $builder->whereIn('trips.type', [TYPE_JOURNEY_SPECIAL, TYPE_JOURNEY, TYPE_JOURNEY_ONLY]);
    }
    if ($city) {
      if (!is_numeric($city)) {
        $city = City::findByName($city)->id;
      }
      $builder->where('trips.city_id', '=', $city);
    }

    $builder->where('trips.account_id', '!=', $user->id);
    $builder->leftJoin('people', function ($join) {
      $join->on('trips.account_id', '=', 'people.account_id');
      $join->on('people.type', '=', \DB::raw(CERT_JOURNEY));
    });
    $builder->leftJoin('driving_licenses', 'people.id', '=', 'driving_licenses.people_id');
    if ($role == 1) {
      $builder->where('trips.created_at', '<', date('Y-m-d H:i:s', time() - 100));
    }
    $builder->select([
      'trips.id',
      'trips.time',
      'trips.start',
      'trips.destination',
      'trips.trip_fee',
      'trips.population',
      'trips.account_id',
      'trips.pool',
      'trips.longitude',
      'trips.latitude',
      'trips.longitude2',
      'trips.latitude2',
      'trips.type',
      'trips.rest_sets',
      'driving_licenses.car_id',
      'trips.role',
      \DB::raw("getDistance($lng,$lat,tr_trips.longitude,tr_trips.latitude) as dis"),
      \DB::raw("getDistance($lng2,$lat2,tr_trips.longitude2,tr_trips.latitude2) as dis2"),
    ]);
    $builder->forPage($page, $size);
    if ($recommend) {
      $builder->having('dis', '<=', 20);
      $builder->having('dis2', '<=', 20);
      $builder->orderBy('dis2');
    } else {
      $builder->orderBy('dis');
    }
    $builder->orderBy('trips.id', 'desc');

    $data = $builder->get();
    return $data;
  }


  /**
   * @return Model
   */
  public function getModel()
  {
    return Trip::class;
  }

  public function accept($id)
  {

    return \DB::transaction(function () use ($id) {
      $user = $this->getUser();
      if ($unfinished = $user->unfinishedTrip()) {
        if ($unfinished->account_id == $user->id && ($unfinished->type == TYPE_JOURNEY || $unfinished->type == TYPE_JOURNEY_SPECIAL || $unfinished->type == TYPE_JOURNEY_ONLY) && $unfinished->role == ROLE_DRIVER) {
          $unfinished->status = TRIP_CANCELED;
          $unfinished->save();
        }
        $type = $unfinished->getReadableType();

        if ($unfinished->account_id == $user->id && $unfinished->role == ROLE_PASSENGER) {
          return $this->respondForbidden("你还有一条未完成的{$type}行程");
        }

        if ($unfinished->account_id != $user->id && $unfinished->type == TYPE_JOURNEY_ONLY) {
          return $this->respondForbidden("你还有一条未完成的{$type}行程");
        }
      }
      $trip = $this->model->find($id);

      if (!$trip) return $this->respondNotFound('行程不存在');

      if ($trip->status != TRIP_PUBLISHED) {
        return $this->respondForbidden('未能抢到该行程:(');
      }

      $trip->status = TRIP_ACCEPTED;

      if ($trip->type == TYPE_JOURNEY_ONLY || $trip->type == TYPE_JOURNEY_SPECIAL) {
        if ($trip->car_model != $user->certJourney()->driving->car_model) {
          return $this->respondForbidden('车辆类型不匹配');
        }
      }

      if ($unfinished) {
        if ($trip->type != $unfinished->type) {
          return $this->respondForbidden('行程类型不匹配');
        }
      }
      $unfinishedOrder = $user->journeyOrderUnfinished();

      if ($unfinishedOrder) {
        $journeyOrder = $unfinishedOrder;
        $populations = $unfinishedOrder->populations();
        if ($populations + $trip->population > 9) {
          return $this->respondForbidden('超出顺风车人数限制');
        }

        /** @var Trip $t */
        $t = $journeyOrder->trips->first();

        if ($t) {
          if ($t->pool != $trip->pool) {
            $str = $trip->pool ? '拼车' : '不拼车';
            return $this->respondForbidden('当前不可接受' . $str . '的行程');
          }

          if ($t->pool) {
            if ($trip->type == TYPE_JOURNEY_ONLY) {
              return $this->respondForbidden('长途包车不能拼单');
            }
            if ($user->journeyOrderUnfinishedCount() >= 4) {
              return $this->respondForbidden('超过拼单数量');
            }
          }
        }
      } else {
        $journeyOrder = JourneyOrder::create([
          'account_id' => $user->id,
        ]);
      }
//      $journeyOrder->amount += $trip->trip_fee;
//
//      $journeyOrder->save();
      $user->receivable = false;
      $user->working = false;
      $user->save();
      TripOrder::create([
        'driver_id' => $user->id,
        'passenger_id' => $trip->account_id,
        'trip_id' => $id,
        'journey_order_id' => $journeyOrder->id,
        'amount' => $trip->trip_fee,
      ]);

      $trip->save();
      $type = $trip->getReadableType();

      $this->transmissionToAccounts($trip->account, "你的{$type}行程被接受", PUSH_JOU_ACCEPTED, ['id' => $trip->id]);

      return $trip;
    });
  }

  public function active($id)
  {
    if (!$trip = Trip::find($id)) {
      return $this->respondNotFound('行程不存在');
    }


    $user = $this->getUser();
    if ($driver = $trip->driver) {
      if ($driver->id != $user->id) {
        return $this->respondForbidden('当前订单不是你的行程订单');
      }
    }

    if ($trip->status < TRIP_IN_POSITION) {
      return $this->respondUnprocessable('行程尚未预付款');
    }


    if ($trip->status == TRIP_CANCELED) {
      return $this->respondForbidden('行程已经被取消');
    }

    return \DB::transaction(function () use ($trip) {

      $trip->status = TRIP_ACTIVE;
      $trip->start_at = Carbon::now();
      $trip->save();

      $this->transmissionToAccounts($trip->account, '你已上车,行程开始', PUSH_JOU_ACTIVE, ['id' => $trip->id]);

      return $trip;
    });
  }

  public function finish($id)
  {
    return \DB::transaction(function () use ($id) {
      if (!$trip = Trip::find($id)) {
        return $this->respondNotFound('行程不存在');
      }

      if ($trip->status < TRIP_ACTIVE) {
        return $this->respondForbidden('行程尚未开始');
      }

      if ($trip->status == TRIP_CANCELED) {
        return $this->respondForbidden('行程已经被取消');
      }
      $trip->status = TRIP_FINISHED;
      $distance = $trip->distance / 1000;

      if ($city = $trip->city) {
        $rule = $city->journeyRule->whereLoose('type', $trip->type)->whereLoose('car_model_id', $trip->car_model);
        if (!count($rule)) $rule = JourneyPayRule::defaultRules()->whereLoose('type', $trip->type)->whereLoose('car_model_id', $trip->car_model);
      } else {
        $rule = JourneyPayRule::defaultRules()->whereLoose('type', $trip->type)->whereLoose('car_model_id', $trip->car_model);
      }

      /** @var JourneyPayRule $r */
      $r = $rule->first();

      $startFee = $r->init_price;

      if ($distance - $r->limit2 >= 0) {
        $diff = $distance - $r->limit;
        $price = $r->distance_price;
      } else {
        $diff = $distance - $r->limit;
        $diff = $diff < 0 ? 0 : $diff;
        $price = $r->less_price;
      }

      $distanceFee = $price * $diff;

      foreach ($trip->orders as $order) {
        $order->start_fee = $startFee;
        $order->distance = $distance * 1000;
        $order->distance_fee = $distanceFee;
        $times = 1;
        $people = 1;
        if ($trip->pool == 0) {
          if ($trip->type == TYPE_JOURNEY) {
            $times = 2;
          } elseif ($trip->type == TYPE_JOURNEY_SPECIAL) {
            $times = 4;
          }
        }
        if ($trip->type == TYPE_JOURNEY || $trip->type == TYPE_JOURNEY_SPECIAL) {
          $people = $trip->population;
        }
        $startFee = $startFee * $times * $people;
        $distanceFee = $distanceFee * $times * $people;
        $order->amount = $startFee + $distanceFee;
        $order->save();
      }
      $trip->save();

      $this->transmissionToAccounts($trip->account, '行程已结束', PUSH_JOU_FINISHED, ['id' => $trip->id]);

      return $trip;
    });
  }

  public function cancel($id, $reason = '')
  {
    $trip = Trip::find($id);

    if (!$trip) {
      return $this->respondNotFound('行程不存在');
    }
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
        if (env('IS_FINE')) {
          if ($user->orderCanceledToday(TYPE_JOURNEY, ROLE_TYPE)->count() >= 2 && $trip->time - time() < 10 * 60) {
            $this->changeBalance($user, -$punishment, '取消行程扣除', false);
          }
        }

        $order->status = ORDER_CANCELED;
        $order->cancel_reason = $reason;
        $order->save();
        OrderCancel::create([
          'account_id' => $user->id,
          'role' => ROLE_TYPE,
          'type' => TYPE_JOURNEY,
          'order_id' => $order->id,
        ]);
        $pushType = ROLE_TYPE ? PUSH_JOU_DIR_CANCELED : PUSH_JOU_PAS_CANCELED;

        $target = ROLE_TYPE ? $order->passenger : $order->driver;
        $canceller = ROLE_TYPE ? '司机' : '乘客';

        //补偿
        if(!env('IS_FINE')) $punishment = 0;
        $id = ROLE_TYPE ? $order->passenger_id : $order->driver_id;
        Account::whereId($id)->increment('balance', $punishment);

        $this->transmissionToAccounts($target, "行程被{$canceller}取消", $pushType, ['id' => $trip->id]);
      }
      $trip->save();

      return $trip;
    });
  }

  public function related($id)
  {
    $trip = Trip::find($id);

    if (!$trip) {
      return $this->respondNotFound('行程不存在');
    }

    return $trip->relatedWithoutSelf;
  }

  //乘客抢单接口
  public function passengerAccept($id)
  {
    return \DB::transaction(function () use ($id) {
      $user = $this->getUser();
      if ($unfinished = Trip::where('account_id', '=', $user->id)->whereStatus(0)->whereIn('type', [TYPE_JOURNEY_SPECIAL, TYPE_JOURNEY, TYPE_JOURNEY_ONLY])->first()) {
        if (time() - $unfinished->created_at > 100) {
          return $this->respondForbidden('已超时');
        }
        if (!in_array($unfinished->type, [TYPE_JOURNEY, TYPE_JOURNEY_ONLY, TYPE_JOURNEY_SPECIAL])) {
          return $this->respondForbidden('参数错误');
        }
        $trip = Trip::find($id);
        if (!$trip) {
          return $this->respondForbidden('参数错误');
        }
        if ($trip->role != 1) {
          return $this->respondForbidden('参数错误');
        }
        if ($trip->status != TRIP_PUBLISHED) {
          return $this->respondForbidden('没有抢到该订单');
        }
        if ($unfinished->type != $trip->type) {
          return $this->respondForbidden('行程类型不匹配');
        }
        if ($unfinished->population > $trip->rest_sets) {
          return $this->respondForbidden('没有多余的座位了');
        }
        $tripOrder = tripOrder::whereStatus(ORDER_ORDERED)->where('driver_id', '=', $trip->account_id)->where('journey_order_id', '!=', 0)->with('trip')->get();
        if ($tripOrder->count() > 0) {
          if ($trip->pool != $tripOrder[0]->trip->pool) {
            return $this->respondForbidden('行程类型不匹配');
          }
          if ($tripOrder->count() >= 4) {
            return $this->respondForbidden('该司机已超过拼单数量');
          }
        }

        $journeyOrder = JourneyOrder::create([
          'account_id' => $unfinished->account_id,
        ]);
        TripOrder::create([
          'driver_id' => $trip->account_id,
          'passenger_id' => $user->id,
          'trip_id' => $unfinished->id,
          'journey_order_id' => $journeyOrder->id,
          'amount' => $unfinished->trip_fee,
        ]);
        $unfinished->status = TRIP_ACCEPTED;
        $unfinished->save();
        $driver = Account::find($trip->account_id);
        $driver->working = false;
        $driver->receivable = false;
        $driver->save();
        $trip->status = TRIP_CANCELED;
        $trip->save();
        $this->pushToAccounts($driver, '有乘客选定您接单', PUSH_CHA_JOU_DRIVER_SELECTED, [
          'id' => $unfinished->id
        ]);
        return true;
      } else {
        return $this->respondForbidden('参数错误');
      }
    });
  }

}