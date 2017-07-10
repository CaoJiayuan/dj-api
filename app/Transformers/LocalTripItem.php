<?php

namespace App\Transformers;

use App\Entity\Trip;
use App\Entity\TripOrder;
use App\Traits\Authenticated;
use App\Traits\Payment;
use League\Fractal\TransformerAbstract;

class LocalTripItem extends TransformerAbstract
{

  use Authenticated, Payment;

  /**
   * @param Trip $item
   * @return array
   */
  public function transform($item)
  {
    /** @var TripOrder $order */
    $order = $item->order;

    $realName = null;

    $carType = null;
    $carId = null;

    if ($item->role == ROLE_DRIVER) {
      $isDriver = true;
    } else {
      $user = $this->getUser();
      if ($user) {
        $isDriver = $order ? ($order->driver_id == $user->id ? true : false) : false;
      } else {
        $isDriver = false;
      }
    }
    if ($order) {
      if ($driver = $order->driver) {
        $cert = null;

        $certType = -1;
        if ($item->type == TYPE_JOURNEY) {
          $certType = CERT_JOURNEY;
        } else if ($item->type == TYPE_LOCAL) {
            $certType = CERT_CAR;
        } else if ($item->type == TYPE_CHAUFFEUR || $item->type == TYPE_CHAUFFEUR_JOURNEY) {
          $certType = CERT_CHAUFFEUR;
        } else if ($item->type == TYPE_TRUCK) {
          $certType = CERT_TRUCK;
        }
        $cert = $driver->cert($certType);
        $realName = $driver->nickname ?: substr($driver->username, -4);

        if ($cert) {

          if ($cert->driving) {
            $type = $cert->driving->carModel();
            $carId = $cert->driving->car_id;
            $carType = array_get($type, 'name');
          }
        }
      }
      if ($item->status < TRIP_PAYED) {
        $amount = $order->amount - $order->pre_pay;
        $user = $this->getUser();
        if ($user) {

          if ($isDriver) {
            $user->credits = 0;
          }

          list($cashAmount, $creditAmount) = $this->getTripPayData($user, $item->type, $amount);
          $order->cash_amount = $cashAmount;
          $order->credit_amount = $creditAmount;
        }
      }
      $order->platform_fee = 0;
    }

    if ($truck = $item->truck) {
      $truck = array_get_values([
        'type.name',
        'width',
        'height',
        'length',
        'truck_type_id',
      ], $truck);
    }

    if (in_array($item->type, [TYPE_CHAUFFEUR, TYPE_LOCAL])) {
      $item->time = $item->created_at;
    }
    if ($order && $isDriver && $item->isFinished()) {
      $platformFee = round(($order->amount - $order->credit_amount) * PLATFORM_RATE);
      $order->platform_fee = $platformFee;
      $order->pre_pay *= (1 - PLATFORM_RATE);
      $order->pre_pay = round($order->pre_pay);
      $order->cash_amount = $order->amount - $order->pre_pay - $order->credit_amount - $platformFee;
      $order->amount -= $platformFee;
    }

    $total = $item->population;

    foreach ($item->relatedWithoutSelf as $related) {
      $total += $related->population;
    }

    if ($order) {
      $order->real_pay = $order->amount - $order->credit_amount - $order->pre_pay;
    }

    $data = [
      'id'            => $item->id,
      'start'         => $item->start,
      'destination'   => $item->destination,
      'latitude'      => $item->latitude,
      'longitude'     => $item->longitude,
      'latitude2'     => $item->latitude2,
      'longitude2'    => $item->longitude2,
      'type'          => $item->type,
      'status'        => $item->status,
      'time'          => $item->time,
      'time_end'      => $item->time_end,
      'trip_fee'      => $item->trip_fee,
      'city_id'       => $item->city_id,
      'distance'      => $item->distance,
      'score'         => $item->score(),
      'truck'         => $truck,
      'population'    => $item->population,
      'total'         => $total,
      'pool'          => $item->pool,
      'related'       => count($item->relatedWithoutSelf),
      'driver'        => $order ? [
        'id'       => $order->driver->id,
        'avatar'   => $order->driver->avatar,
        'phone'    => $order->driver->username,
        'sex'      => $order->driver->sex,
        'name'     => $realName,
        'car_type' => $carType,
        'car_id'   => $carId,
      ] : null,
      'passenger'     => $order ? [
        'id'       => $order->passenger->id,
        'avatar'   => $order->passenger->avatar,
        'phone'    => $order->passenger->username,
        'sex'      => $order->passenger->sex,
        'nickname' => $order->passenger->nickname,
      ] : null,
      'payment'       => $item->isFinished() ? array_get_values([
        'start_fee',
        'amount',
        'distance',
        'back_fee',
        'distance_fee',
        'duration',
        'duration_fee',
        'cash_amount',
        'credit_amount',
        'amount',
        'pre_pay',
        'platform_fee',
        'real_pay'
      ], $order) : null,
      'cancel_reason' => $item->status == TRIP_CANCELED ? ($order ? $order->cancel_reason : null) : null,
      'is_driver'     => $isDriver,
      'now'           => time(),
    ];

    return $data;
  }
}