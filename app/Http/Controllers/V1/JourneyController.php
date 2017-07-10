<?php
/**
 * JourneyRepository.php
 * Date: 16/5/16
 * Time: 下午4:40
 */

namespace App\Http\Controllers\V1;


use App\Entity\City;
use App\Entity\JourneyPayRule;
use App\Repositories\JourneyRepository;
use App\Traits\Certificated;
use App\Traits\OneActionCheck;
use App\Traits\PageAble;
use App\Traits\TripAndOrders;
use App\Transformers\JourneyTripItem;
use App\Transformers\LocalTripItem;
use Carbon\Carbon;

class JourneyController extends BaseController
{
  use TripAndOrders, OneActionCheck, PageAble, Certificated;

  public function getList(JourneyRepository $repository, JourneyTripItem $journeyTripItem)
  {
    $this->validate($this->request, [
      'city' => 'required',
      'lng' => 'required',
      'lat' => 'required',
    ]);
    if (ROLE_TYPE == ROLE_DRIVER) {
      $role = ROLE_PASSENGER;
    }else{
      $role = ROLE_DRIVER;
    }
    $lng = $this->inputGet('lng', 0);
    $lat = $this->inputGet('lat', 0);
    $city = $this->inputGet('city');
    $recommend = $this->inputGet('recommend', 0);
    list($page, $size) = $this->getPageParam($this->request);

    return $this->respondWithCollection($repository->getList($lng, $lat, $role, $page, $size, $city, $recommend), $journeyTripItem);
  }

  public function publish(LocalTripItem $tripItem)
  {
    $this->checkAction();

    if (!LOCAL_APP) {
      $user = $this->getUser();
      if ($user->unpayedTripPassenger()) {
        return $this->respondForbidden('你有个未支付的行程,请你支付后再打车');
      }
    }
    $this->validate($this->request, [
      'start' => 'required',
      'destination' => 'required',
      'time' => 'required',
      'city' => 'required',
      'type' => 'required',
      'car_model' => 'required',
      'people' => 'required',
      'pool' => 'required',
      'longitude' => 'required',
      'latitude' => 'required',
      'latitude2' => 'required',
      'longitude2' => 'required',
    ]);

    $data = $this->inputAll();
    $city = $this->inputGet('city');

    $data['people'] = $this->inputGet('people', 1);
    $cityModel = City::findByNameOrId($city);
    if ($cityModel) {
      $data['city_id'] = $cityModel->id;
    }
    $data['population'] = $data['rest_sets'] = $data['people'];
    $data['status'] = TRIP_PUBLISHED;
    $data['account_id'] = $this->getUser()->id;
    $data['time'] = Carbon::createFromTimestamp($data['time']);
    $data['pool'] = $this->inputGet('pool', 0);

    if ($data['type'] == 1) $data['car_model'] = 0;

    return $this->respondWithItem($this->publishTrip($data, ROLE_TYPE, $data['type']), $tripItem);
  }

  public function predictPay()
  {
    $this->validate($this->request, [
      'city' => 'required',
      'distance' => 'required',
      'car_model' => 'required',
      'type' => 'required',
      'people' => 'required',
      'pool' => 'required',
    ]);

    $people = $this->inputGet('people', 1);
    $pool = $this->inputGet('pool', 0);
    $city = $this->inputGet('city');
    $distance = $this->inputGet('distance');
    $type = $this->inputGet('type');
    $car_model = $this->inputGet('car_model',0);
    if($type==1) $car_model = 0;
    $c = City::findByNameOrId($city);
    if ($c) {
      $rule = $c->journeyRule->whereLoose('type', $type)->whereLoose('car_model_id', $car_model);
      if (!count($rule)) $rule = JourneyPayRule::defaultRules()->whereLoose('type', $type)->whereLoose('car_model_id', $car_model);
    } else {
      $rule = JourneyPayRule::defaultRules()->whereLoose('type', $type)->whereLoose('car_model_id', $car_model);
    }
    $r = $rule->first();
    if ($distance - $r->limit2 >= 0) {
      $diff = $distance - $r->limit;
      $price = $r->distance_price;
    } else {
      $diff = $distance - $r->limit;
      $diff = $diff < 0 ? 0 : $diff;
      $price = $r->less_price;
    }

    if ($type == TYPE_JOURNEY_ONLY) {
      $people = 1;
    }

    $pay = ($r->init_price + $price * $diff) * $people;
    $times = 1;
    if (!$pool) {
      if ($type == TYPE_JOURNEY) {
        $times = 2;
      } elseif ($type == TYPE_JOURNEY_SPECIAL) {
        $times = 4;
      }
      $pay = $pay * $times;
    }

    return ['pay' => round($pay)];
  }

  public function accept(JourneyRepository $repository, LocalTripItem $item)
  {

    if (!$this->hasCertificated(CERT_JOURNEY)) {
      return $this->respondForbidden('你没提交司机认证或认证尚未成功');
    }

    $this->validate($this->request, [
      'id' => 'required',
    ]);

    return $this->respondWithItem($repository->accept($this->inputGet('id')), $item);
  }

  public function active(JourneyRepository $repository, LocalTripItem $item)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    return $this->respondWithItem($repository->active(input_get('id')), $item);
  }


  public function finish(JourneyRepository $repository, LocalTripItem $item)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    return $this->respondWithItem($repository->finish(input_get('id')), $item);
  }

  public function related(JourneyRepository $repository, LocalTripItem $item)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    return $this->respondWithCollection($repository->related(input_get('id')), $item);
  }

  public function cancel(JourneyRepository $repository)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    $id = $this->inputGet('id');
    $reason = $this->inputGet('reason', '');
    $repository->cancel($id, $reason);

    return $this->respondSuccess('行程取消成功');
  }

  //乘客抢单接口
  public function passengerAccept(JourneyRepository $repository)
  {
    $this->validate($this->request, [
      'id' => 'required'
    ]);
    if ($repository->passengerAccept($this->inputGet('id'))) {
      return $this->respondSuccess('抢单成功');
    } else {
      return $this->respondForbidden('抢单失败');
    }
  }
}