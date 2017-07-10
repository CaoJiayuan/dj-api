<?php
/**
 * OrderController.php
 * Date: 16/5/19
 * Time: 下午3:06
 */

namespace App\Http\Controllers\V1;


use App\Entity\ChauffeurJourneyPayRule;
use App\Entity\ChauffeurPayRule;
use App\Entity\City;
use App\Entity\Trip;
use App\Entity\Order;
use App\Repositories\ChauffeurRepository;
use App\Traits\Certificated;
use App\Traits\OneActionCheck;
use App\Traits\PageAble;
use App\Traits\PushMessage;
use App\Traits\TripAndOrders;
use App\Transformers\LocalTripItem;
use Carbon\Carbon;

class ChauffeurController extends BaseController
{
  use PageAble, OneActionCheck, TripAndOrders, PushMessage, Certificated;

  public function publish(ChauffeurRepository $repository)
  {
    $this->checkAction();
    $user = $this->getUser();

    if (!LOCAL_APP) {
      if ($user->unpayedTripPassenger()) {
        return $this->respondForbidden('你有个未支付的行程,请你支付后再打车');
      }
    }
    $this->validateData();
    $data = $this->inputAll();
    $data['type'] = TYPE_CHAUFFEUR;

    $data['account_id'] = $user->id;
    return $repository->publish($data);
  }

  public function journeyList(ChauffeurRepository $repository)
  {
    $this->validate($this->request, [
      'lat' => 'required',
      'lng' => 'required',
    ]);

    list($page, $size) = $this->getPageParam($this->request);

    return $this->respondWithCollection($repository->getList($this->inputGet('lng'), $this->inputGet('lat'), $page,
      $size));
  }

  public function validateData()
  {
    $this->validate($this->request, [
      'start'       => 'required',
      'destination' => 'required',
      'latitude'    => 'required',
      'longitude'   => 'required',
      'latitude2'   => 'required',
      'longitude2'  => 'required',
      'trip_fee'    => 'required',
      'city'        => 'required',
    ]);
  }

  public function predict()
  {
    $this->validate($this->request, [
      'city'     => 'required',
      'distance' => 'required',
    ]);
    $city = $this->inputGet('city');
    $distance = $this->inputGet('distance');

    $c = City::findByNameOrId($city);

    if ($c) {
      $rule = $c->chauffeurPayRule;
    } else {
      $rule = ChauffeurPayRule::defaultRules();
    }
    /** @var ChauffeurPayRule $r */
    $r = $rule->first();
    $diff1 = $distance-$r->limit;
    if($diff1<=0){
      $diff1 = 0;
    }else{
      $diff1 = ceil($diff1/$r->limit2);
    }
    $pay = $r->init_price + $diff1 * $r->distance_price;

    return ['pay' => round($pay)];
  }


  public function predictJourney()
  {
    $this->validate($this->request, [
      'city'     => 'required',
      'distance' => 'required',
    ]);
    $city = $this->inputGet('city');
    $distance = $this->inputGet('distance');

    $c = City::findByNameOrId($city);

    if ($c) {
      $rule = $c->chauffeurJourneyPayRule;
    } else {
      $rule = ChauffeurJourneyPayRule::defaultRules();
    }
    /** @var ChauffeurJourneyPayRule $r */
    $r = $rule->first();

    if ($distance > $r->limit) {
      $pay = ($distance / 100) * $r->more_price;
      $back = ($distance / 100) * $r->more_price_back;
    } else {
      $pay = ($distance / 100) * $r->less_price;
      $back = ($distance / 100) * $r->less_price_back;
    }

    return [
      'pay'  => round($pay),
      'back' => round($back),
    ];
  }

  public function accept(ChauffeurRepository $repository, LocalTripItem $item)
  {
    $this->checkAction();
    if (!$this->hasCertificated(CERT_CHAUFFEUR)) {
      return $this->respondForbidden('你没提交司机认证或认证尚未成功');
    }
    $this->validateRequest([
      'id' => 'required',
    ]);

    return $this->respondWithItem($repository->accept(input_get('id')), $item);
  }

  public function cancel(ChauffeurRepository $repository)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);


    $id = $this->inputGet('id');
    $reason = $this->inputGet('reason', '');
    $repository->cancel($id, $reason);

    return $this->respondSuccess('行程取消成功');
  }

  public function active(ChauffeurRepository $repository, LocalTripItem $item)
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    return $this->respondWithItem($repository->active(input_get('id')), $item);
  }


  public function finish(ChauffeurRepository $repository, LocalTripItem $item)
  {
    $this->validateRequest([
      'id'       => 'required',
    ]);
    
    $id = input_get('id');
    $distance = input_get('distance');

    return $this->respondWithItem($repository->finish($id, $distance), $item);
  }

  public function inPosition()
  {
    $this->validateRequest([
      'id' => 'required',
    ]);

    $id = $this->inputGet('id');

    if (!$trip = Trip::find($id)) {
      return $this->respondNotFound('行程不存在');
    }
    
    if ($trip->status > TRIP_IN_POSITION) {
      return $this->respondForbidden('行程已进行或已取消');
    }
    
    $this->matchTypeOr($trip, TYPE_CHAUFFEUR);

    $trip->status = TRIP_IN_POSITION;
    $trip->save();
    
    $user = $this->getUser();
    if ($driver = $trip->driver) {
      if ($driver->id != $user->id) {
        return $this->respondForbidden('当前订单不是你的行程订单');
      }
    }
    
    $this->transmissionToAccounts($trip->account, '司机已到达位置,请尽快上车', PUSH_CHA_IN_POSITION, ['id' => $trip->id]);

    return get_formatted($trip, LocalTripItem::class);
  }

  public function journeyPublish(ChauffeurRepository $repository)
  {
    $this->checkAction();
    $user = $this->getUser();

    if (!LOCAL_APP) {
      if ($user->unpayedTripPassenger()) {
        return $this->respondForbidden('你有个未支付的行程,请你支付后再打车');
      }
    }
    $this->validateData();
    $data = $this->inputAll();
    $data['account_id'] = $user->id;
    $data['type'] = TYPE_CHAUFFEUR_JOURNEY;
    $data['time'] = Carbon::createFromTimestamp(array_get($data, 'time', time()));

    return $repository->publish($data);
  }
}