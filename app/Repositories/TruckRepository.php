<?php
/**
 * TruckRepository.php
 * Date: 16/5/17
 * Time: 上午10:59
 */

namespace App\Repositories;


use Api\StarterKit\Utils\ApiResponse;
use App\Entity\OrderCancel;
use App\Entity\Tour;
use App\Entity\Trip;
use App\Entity\TripOrder;
use App\Entity\TruckPayRule;
use App\Traits\Payment;
use App\Traits\PushMessage;
use App\Traits\TripAndOrders;
use App\Transformers\LocalTripItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Entity\Account;

class TruckRepository extends Repository
{

  use TripAndOrders, ApiResponse, PushMessage, Payment;

  /**
   * @var Trip
   */
  protected $model;


  /**
   * @param $role
   * @param $lng
   * @param $lat
   * @param int $page
   * @param int $limit
   * @return Collection|array
   */
  public function getList($role, $lng, $lat, $page = 1, $limit = 20, $recommend = 0)
  {
    $user = $this->getUser();
    $builder = $this->model->leftJoin('accounts', 'accounts.id', '=', 'trips.account_id');
    $builder->leftJoin('truck_sizes', 'truck_sizes.id', '=', 'trips.truck_size_id');
    $builder->leftJoin('truck_types', 'truck_types.id', '=', 'truck_sizes.truck_type_id');
    $builder->where('trips.role', '=', $role);
    $lat2 = 0;
    $lng2 = 0;
    if ($role == 0) {
      $role = 1;
    } elseif ($role == 1) {
      $role = 0;
    }
    $unfinished = $user->unfinishedTrip(TYPE_TRUCK, $role);
    if ($unfinished) {
      $builder->where('truck_size_id', '=', $unfinished->truck_size_id);
      if ($role == 0) {
        $builder->having('shours', '<=', date('H', $unfinished->time));
        $builder->having('ehours', '>=', date('H', $unfinished->time));
      }
      if ($role == 1) {
        $builder->having('shours', '<=', date('H', $unfinished->time_end));
        $builder->having('shours', '>=', date('H', $unfinished->time));
      }
      $builder->orderBy('trips.time');
    }
    if($role==1)
    {
      $builder->where('trips.created_at','<',date('Y-m-d H:i:s',time()-100));
    }
    $builder->select([
      'trips.id',
      'trips.time',
      'trips.time_end',
      'trips.start',
      'trips.destination',
      'trips.latitude',
      'trips.longitude',
      'trips.latitude2',
      'trips.longitude2',
      'trips.trip_fee',
      'trips.population',
      'accounts.nickname',
      'accounts.username',
      'accounts.avatar',
      'accounts.sex',
      'truck_sizes.width',
      'truck_sizes.height',
      'truck_sizes.length',
      'truck_types.name as truck_name',
      \DB::raw("getDistance($lng,$lat,tr_trips.longitude,tr_trips.latitude) as dis"),
      \DB::raw("getDistance($lng2,$lat2,tr_trips.longitude2,tr_trips.latitude2) as dis2"),
      \DB::raw("EXTRACT(HOUR FROM tr_trips.time) as shours"),
      \DB::raw("EXTRACT(HOUR FROM tr_trips.time_end) as ehours"),
    ]);
    $builder->where('trips.type', '=', TYPE_TRUCK);
    $builder->where('trips.status', '=', TRIP_PUBLISHED);
    $builder->where('trips.account_id', '!=', $user->id);
    $builder->forPage($page, $limit);
    return $builder->get();
  }


  public function publish($data)
  {
    return \DB::transaction(function () use ($data) {
      return $this->publishTrip($data);
    });
  }

  public function accept($id)
  {
    if (!$trip = Trip::find($id)) {
      return $this->respondNotFound('行程不存在');
    }

    if ($trip->status != TRIP_PUBLISHED) {
      return $this->respondForbidden('未能抢到该行程:(');
    }

    return \DB::transaction(function () use ($trip) {
      $user = $this->getUser();
      if ($unfinished = $user->unfinishedTrip()) {
        if ($unfinished->role == ROLE_PASSENGER || $unfinished->type != TYPE_TRUCK) {
          $type = $unfinished->getReadableType();

          return $this->respondForbidden("你还有一条未完成的{$type}行程");
        }

        $unfinished->status = TRIP_CANCELED;
        $unfinished->save();
//
//        if ($unfinished->account_id == $user->id && $unfinished->type == TYPE_TRUCK && $unfinished->role == ROLE_DRIVER) {
//          $unfinished->status = TRIP_FINISHED;
//          $unfinished->save();
//        }
//        $type = $unfinished->getReadableType();
//
//        if ($unfinished->account_id == $user->id && $unfinished->role == ROLE_PASSENGER) {
//          return $this->respondForbidden("你还有一条未完成的{$type}行程.");
//        }
//
//        if ($unfinished->account_id != $user->id && $unfinished->type != TYPE_TRUCK) {
//          return $this->respondForbidden("你还有一条未完成的{$type}行程");
//        }
      }

      $trip->status = TRIP_ACCEPTED;
      $trip->save();
      $user->receivable = false;
      $user->working = false;
      $user->save();

      TripOrder::create([
        'driver_id' => $user->id,
        'passenger_id' => $trip->account_id,
        'trip_id' => $trip->id,
        'amount' => $trip->trip_fee,
      ]);

      $type = $trip->getReadableType();

      $this->transmissionToAccounts($trip->account, "你的{$type}行程被接受", PUSH_TRUCK_ACCEPTED, ['id' => $trip->id]);

      return get_formatted($trip, LocalTripItem::class);
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

      $this->transmissionToAccounts($trip->account, '你的货物已上车,行程开始', PUSH_TRUCK_ACTIVE, ['id' => $trip->id]);

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
        $rule = $city->truckPayRule;
      } else {
        $rule = TruckPayRule::defaultRules();
      }

      /** @var TruckPayRule $r */
      $r = $rule->whereLoose('truck_size_id', $trip->truck_size_id)->first();
      if(!count($r)) $r = TruckPayRule::defaultRules()->whereLoose('truck_size_id', $trip->truck_size_id)->first();

      $startFee = $r->init_price;
      $distanceFee = $r->distance_price * ($distance - 10);

      foreach ($trip->orders as $order) {
        $order->start_fee = $startFee;
        $order->distance = $distance * 1000;
        $order->distance_fee = $distanceFee;
        $order->amount = $startFee + $distanceFee;
        $order->save();
      }

      $trip->save();
      $this->transmissionToAccounts($trip->account, '行程已结束', PUSH_TRUCK_FINISHED, ['id' => $trip->id]);

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

  public function cancel($id, $reason = '')
  {
    if (!$trip = Trip::find($id)) {
      return $this->respondNotFound('行程不存在');
    }
    if ($trip->status > TRIP_IN_POSITION) {
      if ($trip->status == TRIP_CANCELED) {
        return $this->respondForbidden('行程已经被取消');
      }

      return $this->respondForbidden('行程已经开始,不能取消当前行程');
    }
    return \DB::transaction(function () use ($trip, $reason) {
      $user = $this->getUser();
      $trip->status = TRIP_CANCELED;
      foreach ($trip->orders as $order) {

        $punishment = $order->amount * CANCEL_PUNISHMENT_RATE;
        if(env('IS_FINE')) {
          if ($user->orderCanceledToday(TYPE_TRUCK, ROLE_TYPE)->count() >= 2) {
            $this->changeBalance($user, -$punishment, '取消行程扣除', false);
          } else {
            if ($trip->time - time() < 10 * 60) {
              $this->changeBalance($user, -$punishment, '取消行程扣除', false);
            }
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
        $pushType = ROLE_TYPE ? PUSH_TRUCK_DIR_CANCELED : PUSH_TRUCK_PAS_CANCELED;

        $target = ROLE_TYPE ? $order->passenger : $order->driver;
        $canceller = ROLE_TYPE ? '司机' : '乘客';
        $this->transmissionToAccounts($target, "行程被{$canceller}取消", $pushType, ['id' => $trip->id]);
      }
      $trip->save();
      return $trip;
    });
  }

  //乘客抢单
  public function passengerAccept($id)
  {
    return \DB::transaction(function () use ($id) {
      $trip = Trip::find($id);
      if(!$trip)
      {
        return $this->respondForbidden('参数错误');
      }
      if($trip->status!=TRIP_PUBLISHED)
      {
        return $this->respondForbidden('你没有抢到该订单');
      }
      $user = $this->getUser();
      if($unfinished = $user->unfinishedTrip(TYPE_TRUCK,ROLE_PASSENGER))
      {
        if(time()-$unfinished->created_at>100)
        {
          return $this->respondForbidden('已超时');
        }
        if($unfinished->truck_size_id!=$trip->truck_size_id)
        {
          return $this->respondForbidden('车辆类型不匹配');
        }
      }else{
        return $this->respondForbidden('参数错误');
      }
      $unfinished->status = TRIP_ACCEPTED;
      $trip->status = TRIP_CANCELED;
      $trip->save();
      $unfinished->save();
      TripOrder::create([
        'driver_id' => $trip->account_id,
        'passenger_id' => $user->id,
        'trip_id' => $unfinished->id,
        'amount' => $unfinished->trip_fee,
      ]);
      $driver = Account::find($trip->account_id);
      $driver->working = false;
      $driver->receivable = false;
      $driver->save();
      $this->pushToAccounts($driver,'有乘客选定您接单',PUSH_CHA_TRUCK_DRIVER_SELECTED , [
        'id' => $unfinished->id,
      ]);
      return true;
    });
  }
}