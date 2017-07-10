<?php
/**
 * OrderController.php
 * Date: 16/5/19
 * Time: 下午3:06
 */

namespace App\Http\Controllers\V1;


use App\Entity\Order;
use App\Repositories\OrderRepository;

class PaymentController extends BaseController
{

  public function balanceTripPay(OrderRepository $repository)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    $id = $this->inputGet('id');

    return $repository->balancePay($id);
  }

  public function balancePrePay(OrderRepository $repository)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    return $repository->balancePrePay(input_get('id'));
  }

  public function balanceInsurePay(OrderRepository $repository)
  {
    $this->validateRequest([
      'id'      => 'required',
      'address' => 'required',
    ]);

    $repository->balancePayInsure(input_get('id'),input_get('address'));

    return $this->respondSuccess('支付成功');
  }


  public function balanceWashPay(OrderRepository $repository)
  {
    $this->validate($this->request, [
      'id'     => 'required',
      'amount' => 'required',
    ]);
    $id = $this->inputGet('id');
    $amount = $this->inputGet('amount', 0);
    $order = $repository->balancePayWash($id, $amount);

    return $this->respondWithItem($order);
  }


  public function getWashPayData(OrderRepository $repository)
  {
    $this->validate($this->request, [
      'id'      => 'required',
      'amount'  => 'required',
      'channel' => 'required|in:alipay,wx,upacp',
    ]);

    $id = $this->inputGet('id');
    $amount = $this->inputGet('amount', 0);
    $channel = $this->inputGet('channel');

    return $this->respondSuccess($repository->getWashCharge($id, $amount, $channel));
  }

  public function getRechargeData(OrderRepository $repository)
  {
    $this->validateRequest([
      'channel' => 'required|in:alipay,wx,upacp',
      'amount'  => 'required|integer',
    ]);

    $channel = $this->inputGet('channel');
    $amount = $this->inputGet('amount', 0);

    return $this->respondSuccess($repository->getRechargeCharge($channel, $amount));
  }

  public function getPrePayData(OrderRepository $repository)
  {
    $this->validateRequest([
      'id'      => 'required',
      'channel' => 'required|in:alipay,wx,upacp',
    ]);
    $id = $this->inputGet('id');
    $channel = $this->inputGet('channel');

    return $this->respondSuccess($repository->getPrePayCharge($id, $channel));
  }

  public function getInsureData(OrderRepository $repository)
  {
    $this->validateRequest([
      'id'      => 'required',
      'channel' => 'required|in:alipay,wx,upacp',
      'address' => 'required',

    ]);
    $id = $this->inputGet('id');
    $channel = $this->inputGet('channel');
    $address = $this->inputGet('address');

    return $this->respondSuccess($repository->getInsureData($id, $channel, $address));
  }

  public function getTripPayData(OrderRepository $repository)
  {
    $this->validateRequest([
      'id'      => 'required',
      'channel' => 'required|in:alipay,wx,upacp',
    ]);
    $id = $this->inputGet('id');
    $channel = $this->inputGet('channel');

    return $this->respondSuccess($repository->getTripCharge($id, $channel));
  }
}