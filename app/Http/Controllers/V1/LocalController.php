<?php
/**
 * LocalController.php
 * Date: 16/5/16
 * Time: 下午5:35
 */

namespace App\Http\Controllers\V1;


use App\Entity\Account;
use App\Entity\City;
use App\Entity\LocalPayRule;
use App\Entity\Tour;
use App\Entity\Trip;
use App\Repositories\LocalRepository;
use App\Traits\Certificated;
use App\Traits\OneActionCheck;
use App\Traits\PageAble;
use App\Traits\PushMessage;
use App\Traits\TripAndOrders;
use App\Transformers\LocalTripItem;
use Carbon\Carbon;
use Log;

class LocalController extends BaseController
{

  use OneActionCheck, PushMessage, Certificated, TripAndOrders, PageAble;

  /**
   * 乘客市内打车
   * @param LocalRepository $repository
   * @return mixed
   */
  public function taxi(LocalRepository $repository)
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
      'latitude' => 'required',
      'longitude' => 'required',
      'latitude2' => 'required',
      'longitude2' => 'required',
      'trip_fee' => 'required',
      'city' => 'required',
    ]);

    $tableId = $this->inputGet('table_id');
    $data = $this->inputAll();

    $data['account_id'] = $this->getUser()->id;

    $data = $repository->taxi($tableId, $data);

    return $data;
  }

  public function pushDrivers(LocalRepository $repository)
  {
    $this->validate($this->request, [
      'drivers' => 'required',
      'id' => 'required',
    ]);
    $id = $this->inputGet('id');
    $drivers = $this->inputGet('drivers');
    Log::info(json_encode($drivers));
    $trip = Trip::find($id);
    if (!$trip || (time() - $trip->created_at > LOCAL_TRIP_TIME_LIMIT * 60)) {
      return $this->respondForbidden('行程已经超时作废');
    }

    $repository->doPush($drivers, $trip);

    return $this->respondSuccess();
  }

  public function predictPay()
  {
    $this->validate($this->request, [
      'city' => 'required',
      'distance' => 'required',
      'duration' => 'required',
    ]);

    $city = $this->inputGet('city');
    $distance = $this->inputGet('distance');
    $duration = $this->inputGet('duration');

    $c = City::findByNameOrId($city);

    $cars = [
      CAR_MODEL_A => 'a',
      CAR_MODEL_B => 'b',
      CAR_MODEL_C => 'c',
    ];

    if ($c) {
      $rules = $c->localPayRule;
      $result = [];

      foreach ($rules as $item) {
        $diff = $distance - $item->limit;
        if ($diff < 0) $diff = 0;
        $key = $cars[$item->car->type];
        $result[$key] =
          round($item['duration_price'] * $duration + $item['distance_price'] * $diff + $item['init_price']);
      }

      $default = LocalPayRule::defaultRules();

      $d = [];
      foreach ($default as $r) {
        $diff = $distance - $r['limit'];
        if ($diff < 0) $diff = 0;
        $k = $cars[$r->car->type];
        $d[$k] = round($r['duration_price'] * $duration + $r['distance_price'] * $diff + $r['init_price']);
      }

      return array_merge($d, $result);
    }

    return [
      'a' => 0,
      'b' => 0,
      'c' => 0,
    ];
  }

  /**
   * 司机接受行程
   * @param LocalRepository $repository
   * @return mixed
   */
  public function acceptTrip(LocalRepository $repository)
  {
    $this->checkAction();

    if (!$this->hasCertificated(CERT_CAR)) {
      return $this->respondForbidden('你没提交司机认证或认证尚未成功');
    }

    $this->validate($this->request, [
      'id' => 'required',
    ]);

    $tripId = $this->inputGet('id');

    $trip = $repository->acceptTrip($tripId);

    if (!$trip) {
      return $this->respondForbidden('未能抢到该行程:(');
    }

    return $trip;
  }

  /**
   * 司机取消订单
   * @param LocalRepository $repository
   * @return mixed
   */
  public function driverCancelOrder(LocalRepository $repository)
  {
    $this->validate($this->request, [
      'id' => 'required',
    ]);

    $repository->driverCancel($this->inputGet('id'), $this->inputGet('reason'));

    return $this->respondSuccess('订单取消成功');
  }

  /**
   * 乘客取消订单
   * @param LocalRepository $repository
   * @return mixed
   */
  public function passengerCancelOrder(LocalRepository $repository)
  {
    $this->validate($this->request, [
      'id' => 'required',
    ]);

    $repository->passengerCancel($this->inputGet('id'), $this->inputGet('reason'));

    return $this->respondSuccess('订单取消成功');
  }


  /**
   * Driver work or off duty
   * @return mixed
   */
  public function driverWork()
  {
    $this->checkAction();

    $account = $this->getUser();

    if (!$this->hasCertificated(CERT_CAR, $account)) {
      return $this->respondForbidden('你没提交司机认证或认证尚未成功!');
    }

    if ($account->receive_at > time()) {

      $time = $account->receive_at - time();

      return $this->respondForbidden('你还有' . date('i:s', $time) . '可以接受市内打车订单');
    }

    $builder = \DB::table('accounts')->where('id', $account->id);

    $status = $account->working;

    if ($status) {
      $msg = '停止接受订单';
      $builder->update(['receivable' => RECEIVE_CLOSE, 'working' => false]);
    } else {
      $msg = '开始接受订单';
      $builder->update(['receivable' => RECEIVE_OPEN, 'working' => true]);
    }

    return $this->respondSuccess($msg);
  }


  /**
   * 司机到达乘客位置
   */
  public function inPosition()
  {
    $this->validate($this->request, [
      'id' => 'required',
    ]);
    $id = $this->inputGet('id');


    $trip = $this->updateTrip($id, TRIP_IN_POSITION);

    $this->transmissionToAccounts($trip->account, '司机已到达位置,请尽快上车', PUSH_LOC_IN_POSITION, ['id' => $trip->id]);

    return get_formatted($trip, LocalTripItem::class);
  }

  /**
   * 乘客已上车
   */
  public function active()
  {
    $this->validate($this->request, [
      'id' => 'required',
    ]);
    $id = $this->inputGet('id');


    if (!$trip = Trip::find($id)) {
      return $this->respondNotFound('行程不存在');
    }
    $this->matchTypeOr($trip, TYPE_LOCAL);

    $user = $this->getUser();
    if ($driver = $trip->driver) {
      if ($driver->id != $user->id) {
        return $this->respondForbidden('当前订单不是你的行程订单');
      }
    }

    if ($trip->status < TRIP_IN_POSITION) {
      return $this->respondUnprocessable('你未到达乘客位置');
    }

    if ($trip->status > TRIP_IN_POSITION) {
      return $this->respondForbidden('行程已进行或已取消');
    }

    $trip->start_at = Carbon::now();
    $trip->status = TRIP_ACTIVE;
    $trip->save();

    $this->transmissionToAccounts($trip->account, '你已经上车', PUSH_LOC_ACCEPTED, ['id' => $trip->id]);

    return get_formatted($trip, LocalTripItem::class);

  }

  /**
   * 结束行程
   * @param LocalRepository $repository
   * @return mixed
   */
  public function finish(LocalRepository $repository)
  {
    $this->validate($this->request, [
      'id' => 'required',
      'distance' => 'required',
    ]);

    $id = $this->inputGet('id');
    $distance = $this->inputGet('distance');

    $push = $repository->finish($id, $distance);

    return $push;
  }

  public function comment(LocalRepository $repository)
  {
    $this->validateRequest([
      'id' => 'required',
      'score' => 'required|integer',
    ]);

    $id = $this->inputGet('id');
    $score = $this->inputGet('score');

    return $repository->comment($id, $score);
  }


  public function detail()
  {
    $this->validate($this->request, [
      'id' => 'required',
    ]);
    $id = $this->inputGet('id');

    if (!$trip = Trip::find($id)) {
      return $this->respondNotFound('行程不存在');
    }

    return get_formatted($trip, LocalTripItem::class);
  }

  public function mine()
  {
    $user = $this->getUser();

    list($page, $size) = $this->getPageParam($this->request);

    return get_formatted($user->getTrips(TYPE_LOCAL, ROLE_TYPE, $page, $size), LocalTripItem::class);
  }

  public function trip()
  {
    $user = $this->getUser();
    $car = $user->certCar();
    $car_model = $car['driving']['car_model'];
    $this->validate($this->request,[
      'lat' => 'required',
      'lng' => 'required',
    ]);
    $lng = $this->inputGet('lng');
    $lat = $this->inputGet('lat');
    $builder = Trip::whereStatus(0);
    $builder  = $builder->where('car_model','=',$car_model);
    $builder = $builder->where('created_at','>',Carbon::now()->addMinutes(-2));
    $builder = $builder->having('dis','<=',2);
    $builder = $builder->orderBy('created_at','asc');
    $builder = $builder->select(
      'id',
      \DB::raw("getDistance($lng,$lat,tr_trips.longitude,tr_trips.latitude) as dis")
    );
    $trip = $builder->first();
    if(count($trip)){
      unset($trip['dis']);
    }else{
      return collect();
    }
    return $this->respondWithItem($trip);
  }
}