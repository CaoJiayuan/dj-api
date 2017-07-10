<?php
/**
 * TourAndOrders.php
 * Date: 16/5/20
 * Time: 上午9:27
 */

namespace App\Traits;


use App\Entity\Account;
use App\Entity\City;
use App\Entity\Tour;
use App\Entity\Trip;
use Carbon\Carbon;

trait TripAndOrders
{
  use Authenticated, ModelHelper;


  /**
   * @param $data
   * @param int $role
   * @param int $type
   * @return Trip
   */
  public function publishTrip($data, $role = ROLE_PASSENGER, $type = TYPE_LOCAL)
  {
    $data['role'] = arr_get($data, 'role') ?: $role;
    $data['type'] = arr_get($data, 'type') ?: $type;
    $data['distance'] = arr_get($data, 'distance', 0) * 1000;
    if ($city = arr_get($data, 'city')) {
      $c = City::findByNameOrId($city);
      if ($c) {
        $data['city_id'] = $c->id;
      } else {
        $data['city_id'] = 0;
      }
    }
    $trip = $this->copy(Trip::class, $data);

    return $trip;
  }

  /**
   * @param $id
   * @param $status
   * @param bool $forceUpdate
   * @return Trip
   */
  public function updateTrip($id, $status, $forceUpdate = false)
  {
    $map = [
      TRIP_PUBLISHED   => '已发布',
      TRIP_ACCEPTED    => '已接受',
      TRIP_IN_POSITION => '到达乘客位置',
      TRIP_ACTIVE      => '乘客已上车',
      TRIP_FINISHED    => '已完成',
      TRIP_CANCELED    => '已取消',
    ];
    $user = $this->getUser();
    if (!$trip = Trip::find($id)) {
      return $this->respondNotFound('行程不存在');
    }

    if ($trip->type != TYPE_LOCAL && $trip->type != TYPE_CHAUFFEUR) {
      $map[TRIP_IN_POSITION] = '已预付款';
      $map[TRIP_ACTIVE] = '开始行程';
    }

    if ($trip->status + 1 != $status && $status != TRIP_CANCELED) {
      return $this->respondForbidden("当前行程状态为'{$map[$trip->status]}', 不能改变为'{$map[$status]}'");
    }
    if ($driver = $trip->driver) {
      if ($driver->id != $user->id) {
        return $this->respondForbidden('当前订单不是你的行程订单');
      }
    }

    if ($status == TRIP_ACTIVE) {
      $trip->start_at = Carbon::now();
    }

    if ($status == TRIP_FINISHED) {
      $trip->finish_at = Carbon::now();
    }

    $trip->status = $status;
    $trip->save();

    return $trip;
  }

  /**
   * @param Account $user
   * @param Trip $trip
   */
  public function cancelPunish($user, $trip)
  {
    $sum = $user->orderCanceledToday($trip->type, ROLE_TYPE);
    switch ($trip->type) {
      case TYPE_LOCAL:
      case TYPE_CHAUFFEUR:
        if ($sum >= 2) {
          $punishment = $trip->trip_fee * CANCEL_PUNISHMENT_RATE;
//          $this->changeBalance($user, -$punishment, '取消行程扣除', false);
        }
        break;
      case TYPE_CHAUFFEUR_JOURNEY:
      case TYPE_JOURNEY:
      case TYPE_TRUCK:

        break;
    }
  }


  /**
   * @param Trip $trip
   * @param $types
   * @return bool
   */
  public function matchTypeOr($trip, $types)
  {
    if (!in_array($trip->type, (array)$types)) {
      return $this->respondForbidden('行程类型不匹配');
    }
    
    return true;
  }
}