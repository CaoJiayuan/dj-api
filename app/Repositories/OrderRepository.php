<?php
/**
 * OrderRepository.php
 * Date: 16/8/16
 * Time: 下午3:29
 * Created by Caojiayuan
 */

namespace App\Repositories;


use Api\StarterKit\Utils\ApiResponse;
use App\Entity\Account;
use App\Entity\InsureOrder;
use App\Entity\Person;
use App\Entity\PlatformPay;
use App\Entity\Trip;
use App\Entity\WashOrder;
use App\Traits\Payment;
use App\Traits\PingXXHelper;
use App\Traits\PushMessage;
use App\Transformers\LocalTripItem;
use Illuminate\Database\Eloquent\Model;

class OrderRepository extends Repository
{
  use PingXXHelper, ApiResponse, Payment, PushMessage;

  /**
   * @param $id
   * @param $channel
   * @return string
   */
  public function getTripCharge($id, $channel)
  {
    $trip = Trip::find($id);

    if (!$trip) {
      return $this->respondNotFound('行程不存在');
    }
    if ($trip->status < TRIP_FINISHED) {
      return $this->respondForbidden('行程尚未结束');
    }

    if ($trip->status > TRIP_FINISHED) {
      return $this->respondForbidden('行程已付款或已取消');
    }
    
    $amount = $trip->order->amount - $trip->order->pre_pay;

    $charge = $this->createCharge($channel, str_random(), $amount,
      ['type' => CHARGE_TYPE_TRIP_PAY, 'id' => $id]);

    return $charge->__toString();
  }

  public function getPrePayCharge($id, $channel)
  {
    $trip = Trip::find($id);

    if (!$trip) {
      return $this->respondNotFound('行程不存在');
    }

    if (!$order = $trip->order) {
      return $this->respondForbidden('行程未被接受或已取消');
    }

    if ($trip->status > TRIP_IN_POSITION) {
      return $this->respondForbidden('行程已进行或已取消');
    }
    $amount = $order->amount;

    $prePayRate = 0.5;
    if($trip->type==TYPE_CHAUFFEUR_JOURNEY) $prePayRate=0.2;
    $charge = $this->createCharge($channel, str_random(), $amount * $prePayRate,
      ['type' => CHARGE_TYPE_PAY_PRE, 'id' => $id]);

    return $charge->__toString();
  }

  public function getRechargeCharge($channel, $amount)
  {
    $user = $this->getUser();

    $charge = $this->createCharge($channel, str_random(), $amount, [
      'type' => CHARGE_TYPE_RECHARGE,
      'id'   => $user->id,
    ]);

    return $charge->__toString();
  }

  /**
   * @return Model
   */
  public function getModel()
  {
    Account::class;
  }

  public function balancePay($id)
  {
    return \DB::transaction(function () use ($id) {
      $trip = Trip::find($id);

      if (!$trip) {
        return $this->respondNotFound('行程不存在');
      }

      if ($trip->status < TRIP_FINISHED) {
        return $this->respondForbidden('行程尚未结束');
      }

      if ($trip->status > TRIP_FINISHED) {
        return $this->respondForbidden('行程已付款或已取消');
      }
      $user = $this->getUser();

      $order = $trip->order;

      $amount = $order->amount - $order->pre_pay;
      if ($user->balance < $amount) {
        return $this->respondForbidden('用户余额不足,请充值');
      }

      list($cashAmount, $creditAmount) = $this->getTripPayData($user, $trip->type, $amount);


      $driver = $order->driver;
      $platformFee = $cashAmount * PLATFORM_RATE;
      PlatformPay::create([
        'driver_id' => $driver->id,
        'trip_id'   => $trip->id,
        'amount'    => $platformFee,
      ]);

      $passenger = $trip->passenger;

      if ($passenger->id == $driver->id) {
        return $this->respondForbidden('不能给自己支付');
      }

      if ($creditAmount > 0) {
        $this->changeCredit($passenger, -$creditAmount, '行程支付');
        $this->changeCredit($trip->driver, $creditAmount, '行程收入');
      }

      $this->changeBalance($trip->driver, $cashAmount - $platformFee, '行程收入');
      $this->changeBalance($passenger, -$cashAmount, '行程支付');

      $this->notificationToAccounts($passenger, '行程支付成功', PUSH_PAYED_SUCCESS, ['id' => $trip->id]);
      $start = $trip->start;
      $dest = $trip->destination;
      $type = $trip->getReadableType();
      $this->notificationToAccounts($trip->driver, "从{$start}到{$dest}的{$type}行程已经付款", PUSH_PAYED_SUCCESS, ['id' => $trip->id]);

      $order->cash_amount = $cashAmount;
      $order->credit_amount = $creditAmount;
      $order->status = ORDER_FINISHED;
      $order->save();
      $trip->status = TRIP_PAYED;
      $trip->save();

      return get_formatted($trip, LocalTripItem::class);
    });
  }


  public function balancePayWash($id, $amount)
  {
    return \DB::transaction(function () use ($id, $amount) {
      $person = Person::find($id);
      if (!$person || !$person->shop) {
        return $this->respondNotFound('商家不存在');
      }
      $shop = $person->shop;
      $seller = $shop->account;
      $user = $this->getUser();
      if ($user->balance < $amount) {
        return $this->respondForbidden('用户余额不足,请充值');
      }
      list($cash, $credit) = $this->getTripPayData($user, TYPE_CAR_WASH, $amount);


      if ($seller->id == $user->id) {
        return $this->respondForbidden('不能给自己支付');
      }
      
      if ($credit > 0) {
        $this->changeCredit($user, -$credit, '洗车支付');
        $this->changeCredit($seller, $credit, '洗车收入');
      }

      $this->changeBalance($user, -$cash, '洗车支付');
      $this->changeBalance($seller, $cash, '洗车支付');

      $name = $user->nickname ?: $user->username;
      $this->notificationToAccounts($seller, "用户{$name}的洗车订单付款成功", PUSH_WASH_PAYED);
      $this->notificationToAccounts($user, '洗车付款成功', PUSH_WASH_PAYED);


      $time = explode('.', round_fix(microtime(true), 5));

      $order = WashOrder::create([
        'people_id'     => $id,
        'order_no'      => 'W-' . date('YmdHis') . end($time),
        'account_id'    => $user->id,
        'amount'        => $amount,
        'cash_amount'   => $cash,
        'credit_amount' => $credit,
        'status'        => ORDER_FINISHED,
      ]);

      return $order;
    });
  }

  public function balancePrePay($id)
  {
    return \DB::transaction(function () use ($id) {
      $trip = Trip::find($id);

      if (!$trip) {
        return $this->respondNotFound('行程不存在');
      }
      
      if ($trip->status > TRIP_IN_POSITION) {
        return $this->respondForbidden('行程已进行或已取消');
      }
      if ($order = $trip->order) {
        $amount = $order->amount * PRE_PAY_RATE;

        if($trip->type==TYPE_CHAUFFEUR_JOURNEY)
        {
          $amount = $order->amount * 0.2;
        }
        
        $driver = $order->driver;
        $platformFee = $amount * PLATFORM_RATE;
        PlatformPay::create([
          'driver_id' => $driver->id,
          'trip_id'   => $trip->id,
          'amount'    => $platformFee,
        ]);
        
        $user = $this->getUser();
        if ($user->balance < $amount) {
          return $this->respondForbidden('用户余额不足,请充值');
        }

        $this->changeBalance($user, -$amount, '行程预付款');
        $this->changeBalance($driver, $amount - $platformFee, '行程预付款收入');

        $start = $trip->start;
        $dest = $trip->destination;
        $type = $trip->getReadableType();
        $this->notificationToAccounts($driver, "从{$start}到{$dest}的{$type}行程已预付款", PUSH_PRE_PAYED, ['id' => $trip->id]);
        $this->notificationToAccounts($user, '行程预付款成功', PUSH_PRE_PAYED, ['id' => $trip->id]);

        $order->pre_pay = $amount;
        $order->save();

        $trip->status = TRIP_IN_POSITION;
        $trip->save();
      }

      return get_formatted($trip, LocalTripItem::class);
    });
  }

  public function balancePayInsure($id, $address)
  {
    \DB::transaction(function () use ($id, $address) {

      if (!$order = InsureOrder::find($id)) {
        return $this->respondNotFound('保险订单不存在');
      }

      if ($order->handled >= INSURE_PAYED) {
        return $this->respondForbidden('保险订单已经支付');
      }
      $amount = $order->amount;
      $user = $this->getUser();

      if ($user->balance < $amount) {
        return $this->respondForbidden('用户余额不足,请充值');
      }

      $this->changeBalance($user, $amount, '保险订单支付');
      $this->notificationToAccounts($user, '保险订单付款成功', PUSH_INSURE_PAYED);

      $order->handled = INSURE_PAYED;
      $order->address = $address;
      $order->save();

      return $order;
    });
  }

  public function getInsureData($id, $channel, $address)
  {
    if (!$order = InsureOrder::find($id)) {
      return $this->respondNotFound('保险订单不存在');
    }
    if ($order->handled >= INSURE_PAYED) {
      return $this->respondForbidden('保险订单已经支付');
    }

    $charge = $this->createCharge($channel, str_random(), $order->amount, [
      'type'    => CHARGE_TYPE_INSURE,
      'id'      => $id,
      'address' => $address,
    ]);

    return $charge->__toString();
  }

  public function getWashCharge($id, $amount, $channel)
  {
    $user = $this->getUser();
    $person = Person::find($id);
    if (!$person || !$person->shop) {
      return $this->respondNotFound('商家不存在');
    }
    $charge = $this->createCharge($channel, str_random(), $amount, [
      'type'       => CHARGE_TYPE_PAY_WASH,
      'id'         => $id,
      'account_id' => $user->id,
    ]);

    return $charge->__toString();
  }
}