<?php
/**
 * TruckController.php
 * Date: 16/5/17
 * Time: 上午11:01
 */

namespace App\Http\Controllers\V1;


use App\Entity\City;
use App\Entity\TruckPayRule;
use App\Repositories\TruckRepository;
use App\Traits\Certificated;
use App\Traits\OneActionCheck;
use App\Traits\PageAble;
use App\Transformers\LocalTripItem;
use Carbon\Carbon;

class TruckController extends BaseController
{

  use PageAble, OneActionCheck, Certificated;

  public function getList(TruckRepository $repository)
  {
    $this->validate($this->request, [
      'lng' => 'required',
      'lat' => 'required',
    ]);
    $lng = $this->inputGet('lng', 0);
    $lat = $this->inputGet('lat', 0);
    $recommend = $this->inputGet('recommend',0);
    $role = ROLE_TYPE ? ROLE_PASSENGER : ROLE_DRIVER;

    list($page, $size) = $this->getPageParam($this->request);

    return $this->respondWithCollection($repository->getList($role, $lng, $lat, $page, $size,$recommend));
  }


  public function publish(TruckRepository $repository, LocalTripItem $tripItem)
  {
    $this->checkAction();
    $this->validate($this->request, [
      'truck_size_id' => 'required',
      'time'          => 'required',
    ]);

    if ($this->inputGet('truck_size_id', 0) < 1) {
      return $this->respondUnprocessable('货车尺寸输入不正确');
    }

    $user = $this->getUser();
    if (!LOCAL_APP) {
      if ($user->unpayedTripPassenger()) {
        return $this->respondForbidden('你有个未支付的行程,请你支付后再打车');
      }
    }
    $data = $this->inputAll();
    if (!arr_get($data, 'time')) {
      $data['time'] = time();
    }
    
    if (!arr_get($data, 'time_end')) {
      $data['time_end'] = time() + 60 * 10;
    }
    
    $data['time'] = Carbon::createFromTimestamp($data['time']);
    $data['time_end'] = Carbon::createFromTimestamp($data['time_end']);
    $data['role'] = ROLE_TYPE;
    $data['type'] = TYPE_TRUCK;
    $data['account_id'] = $user->id;

    return $this->respondWithItem($repository->publish($data), $tripItem);
  }

  public function accept(TruckRepository $repository)
  {
    if (!$this->hasCertificated(CERT_TRUCK)) {
      return $this->respondForbidden('你没提交司机认证或认证尚未成功');
    }
    $this->validateRequest([
      'id' => 'required',
    ]);

    return $repository->accept(input_get('id'));
  }

  public function active(TruckRepository $repository, LocalTripItem $tripItem)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    return $this->respondWithItem($repository->active(input_get('id')), $tripItem);
  }

  public function finish(TruckRepository $repository, LocalTripItem $tripItem)
  {
    $this->validateRequest([
      'id'       => 'required',
    ]);

    $id = $this->inputGet('id');

    $trip = $repository->finish($id);
    
    return $this->respondWithItem($trip, $tripItem);
  }

  public function cancel(TruckRepository $repository)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    $id = $this->inputGet('id');
    $reason = $this->inputGet('reason', '');
    $repository->cancel($id, $reason);

    return $this->respondSuccess('行程取消成功');
  }

  public function predictPay()
  {
    $this->validate($this->request, [
      'truck_size_id' => 'required',
      'distance'      => 'required',
      'city'          => 'required',
    ]);

    $size = $this->inputGet('truck_size_id');
    $distance = $this->inputGet('distance')-10;
    $city = $this->inputGet('city');

    $c = City::findByNameOrId($city);
    if ($c) {
      /** @var TruckPayRule $rule */
      $rule = $c->truckPayRule->whereLoose('truck_size_id', $size)->first();
      if(!count($rule)) $rule = TruckPayRule::defaultRules()->whereLoose('truck_size_id', $size)->first();
    } else {
      $rule = TruckPayRule::defaultRules()->whereLoose('truck_size_id', $size)->first();
    }
    if ($rule) {
      return ['pay' => round($rule->init_price + $rule->distance_price * $distance)];
    }
    return ['pay' => 0];
  }
  //乘客抢单
  public function passengerAccept(TruckRepository $repository)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);
    if($repository->passengerAccept(input_get('id')))
    {
      return $this->respondSuccess('抢单成功');
    }else{
      return $this->respondForbidden('抢单失败');
    }
  }
}