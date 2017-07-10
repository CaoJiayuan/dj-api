<?php
/**
 * OrderController.php
 * Date: 16/5/19
 * Time: 下午3:06
 */

namespace App\Http\Controllers\V1;


use App\Entity\Account;
use App\Entity\InsureOrder;
use App\Entity\Order;
use App\Entity\Person;
use App\Entity\PlatformPay;
use App\Entity\Trip;
use App\Entity\WashOrder;
use App\Traits\Payment;
use App\Traits\PushMessage;

class PingXXController extends BaseController
{
  use PushMessage, Payment;

  public function hook()
  {
    $inputs = $this->inputAll();
    $info = array_get($inputs, 'data.object.description');
    $amount = array_get($inputs, 'data.object.amount', 0);

    if ($info) {
      $data = json_decode($info, true);

      $type = array_get($data, 'type');

      $id = array_get($data, 'id', 0);
      switch ($type) {
        case CHARGE_TYPE_RECHARGE :
          $this->recharge($id, $amount);
          break;
        case CHARGE_TYPE_TRIP_PAY :
          $this->tripPay($id, $amount);
          break;
        case CHARGE_TYPE_PAY_PRE:
          $this->prePay($id, $amount);
          break;
        case CHARGE_TYPE_PAY_WASH:
          $this->washPay($id, array_get($data, 'account_id'), $amount);
          break;
        case CHARGE_TYPE_INSURE:
          $this->insurePay($id, $amount, array_get($data, 'address', ''));
          break;
      }

      return $data;
    }

    return array_get($inputs, 'data.object');
  }


  public function recharge($id, $amount)
  {
    $user = Account::find($id);
    if ($user) {
      $this->changeBalance($user, $amount, '充值');
      $this->changeCredit($user, $amount, '充值');
      $this->notificationToAccounts($user, '充值成功', PUSH_RECHARGE_SUCCESS);
    }

    return true;
  }

  public function tripPay($id, $amount)
  {
    if ($trip = Trip::find($id)) {
      if ($trip->status != TRIP_FINISHED) {
        return false;
      }

      return \DB::transaction(function () use ($trip, $amount) {
        if ($order = $trip->order) {
          
          $order->cash_amount = $amount;

          $driver = $order->driver;
          $platformFee = $amount * PLATFORM_RATE;
          PlatformPay::create([
            'driver_id' => $driver->id,
            'trip_id'   => $trip->id,
            'amount'    => $platformFee,
          ]);
          
          $this->changeBalance($trip->driver, $amount - $platformFee, '行程收入');
          $this->notificationToAccounts($trip->passenger, '行程支付成功', PUSH_PAYED_SUCCESS, ['id' => $trip->id]);
          $start = $trip->start;
          $dest = $trip->destination;
          $type = $trip->getReadableType();
          $this->notificationToAccounts($trip->driver, "从{$start}到{$dest}的{$type}行程已经付款", PUSH_PAYED_SUCCESS, ['id' => $trip->id]);
          $order->status = ORDER_FINISHED;
          $order->save();
          $trip->status = TRIP_PAYED;
          $trip->save();
        }

        return $trip;
      });
    }

    return true;
  }

  public function washPay($id, $userId, $amount)
  {
    return \DB::transaction(function () use ($id, $userId, $amount) {
      if (!$userId) {
        return false;
      }

      if (!$user = Account::find($userId)) {
        return false;
      }
      $person = Person::find($id);
      if (!$person || !$person->shop) {
        return false;
      }

      $time = explode('.', round_fix(microtime(true), 5));
      $shop = $person->shop;
      $seller = $shop->account;
      $name = $user->nickname ?: $user->username;
      $this->notificationToAccounts($seller, "用户{$name}的洗车订单付款成功", PUSH_WASH_PAYED);
      $this->notificationToAccounts($user, '洗车付款成功', PUSH_WASH_PAYED);


      $order = WashOrder::create([
        'people_id'   => $id,
        'order_no'    => 'W-' . date('YmdHis') . end($time),
        'account_id'  => $user->id,
        'amount'      => $amount,
        'cash_amount' => $amount,
        'status'      => ORDER_FINISHED,
      ]);

      return $order;
    });
  }

  public function prePay($id, $amount)
  {
    if ($trip = Trip::find($id)) {
      \DB::transaction(function () use ($trip, $amount) {
        if ($order = $trip->order) {

          $user = $order->passenger;

          $driver = $order->driver;
          $platformFee = $amount * PLATFORM_RATE;
          PlatformPay::create([
            'driver_id' => $driver->id,
            'trip_id'   => $trip->id,
            'amount'    => $platformFee,
          ]);

          $this->changeBalance($order->driver, $amount - $platformFee, '行程预付款收入');

          $start = $trip->start;
          $dest = $trip->destination;
          $type = $trip->getReadableType();
          $this->notificationToAccounts($order->driver, "从{$start}到{$dest}的{$type}行程已预付款", PUSH_PRE_PAYED, ['id' => $trip->id]);
          $this->notificationToAccounts($user, '行程预付款成功', PUSH_PRE_PAYED, ['id' => $trip->id]);

          $order->pre_pay = $amount;
          $order->save();

          $trip->status = TRIP_IN_POSITION;
          $trip->save();
        }
      });
    }
  }

  public function insurePay($id, $amount, $address)
  {
    \DB::transaction(function () use ($id, $amount, $address) {
      if ($order = InsureOrder::find($id)) {
        $user = $order->account;
        $this->notificationToAccounts($user, '保险订单付款成功', PUSH_INSURE_PAYED);
        $order->address = $address;
        $order->handled = INSURE_PAYED;
        $order->save();
      }
    });
  }
}