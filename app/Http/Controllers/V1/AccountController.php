<?php
/**
 * AccountController.php
 * Date: 16/5/16
 * Time: 下午4:29
 */

namespace App\Http\Controllers\V1;


use App\Entity\CreditCard;
use App\Entity\Feedback;
use App\Repositories\AccountRepository;
use App\Traits\ModelHelper;
use App\Traits\PageAble;
use App\Transformers\AuthTransformer;
use App\Transformers\LocalTripItem;
use App\Entity\City;

class AccountController extends BaseController
{
  use ModelHelper, PageAble;

  public function detail(AuthTransformer $transformer)
  {
    $data = $this->getUser();

    return $this->respondWithItem($data, $transformer);
  }

  public function credit()
  {
    $user = $this->getUser();

    return $this->respondWithCollection($this->forPage($this->request, $user->creditsData));
  }

  public function balance()
  {
    $user = $this->getUser();

    return $this->respondWithCollection($this->forPage($this->request, $user->balanceData));
  }

  public function edit(AccountRepository $repository, AuthTransformer $transformer)
  {
    $account = $repository->edit($this->request->only([
      'nickname',
      'city',
      'avatar',
      'sex',
      'channel_id',
      'device',
    ]));

    return $this->respondWithItem($account, $transformer);
  }

  public function addCreditCard()
  {
    $this->validate($this->request, [
      'username' => 'required',
      'name'     => 'required',
      'card_id'  => 'required',
    ]);

    $data = $this->inputAll();

    $user = $this->getUser();

    $data['account_id'] = $user->id;

    $exists = $user->creditCards->whereLoose('name', $this->inputGet('name'))
      ->whereLoose('card_id', $this->inputGet('card_id'))->first();

    if ($exists) {
      return $this->respondUnprocessable('银行卡已存在');
    }

    $this->copy(CreditCard::class, $data);

    return $this->respondSuccess('添加银行卡成功');
  }


  public function feedback()
  {
    $this->validate($this->request, [
      'content' => 'required',
    ]);
    $user = $this->getUser();

    Feedback::create([
      'account_id' => $user->id,
      'content'    => $this->inputGet('content'),
    ]);

    return $this->respondSuccess('意见反馈提交成功');
  }

  public function creditCards()
  {
    $user = $this->getUser();

    return $this->respondWithCollection($user->creditCards);
  }

  public function withdraw(AccountRepository $repository)
  {
    $this->validate($this->request, [
      'amount'         => 'required',
      'credit_card_id' => 'required',
    ]);


    $repository->withDraw($this->inputAll());

    return $this->respondSuccess('提交成功,请等待后台处理');
  }

  public function certCar(AccountRepository $repository)
  {

    $this->validate($this->request, [
      'name'     => 'required',
      'phone'    => 'required',
      'car_id'   => 'required',
      'd_image'  => 'required',
      'dr_image' => 'required',
      //ywl
      'city_id'        => 'required',//城市名
      'policy_photo'   => 'required',
      'policy_photo_2' => 'required',
    ]);
    $user = $this->getUser();

    if ($cert = $user->certCar()) {
      if ($cert->status == CERT_FAILED) {
        $cert->driver->delete();
        $cert->driving->delete();
        $cert->delete();
      } else {
        return $this->respondForbidden('你已经提交过汽车认证了');
      }

    }
    $data = $this->inputAll();
    $city_name = $data['city_id'];
    $city = City::findByName($data['city_id']);
    if($city){
      $data['city_id'] = $city->id;
    }else{
      return $this->respondForbidden('找不到该城市');
    }
    $data['account_id'] = $user->id;
    $data['car_model'] = $this->inputGet('car_type',0);
    $data['type'] = CERT_CAR;
    $data['status'] = CERT_UNREVIEWED;

    return $repository->certCar($data,$city_name);
  }

  public function certJourney(AccountRepository $repository)
  {

    $this->validate($this->request, [
      'name'     => 'required',
      'phone'    => 'required',
      'car_id'   => 'required',
      'd_image'  => 'required',
      'dr_image' => 'required',
      //ywl
      'city_id'        => 'required',
      'policy_photo'   => 'required',
      'policy_photo_2' => 'required',
    ]);
    $user = $this->getUser();

    if ($cert = $user->certJourney()) {
      if ($cert->status == CERT_FAILED) {
        $cert->driver->delete();
        $cert->driving->delete();
        $cert->delete();
      } else {
        return $this->respondForbidden('你已经提交过顺风车认证了');
      }
    }
    $data = $this->inputAll();
    $city_name = $data['city_id'];
    $city = City::findByName($data['city_id']);
    if($city){
      $data['city_id'] = $city->id;
    }else{
      return $this->respondForbidden('找不到该城市');
    }
    $data['account_id'] = $user->id;
    $data['type'] = CERT_JOURNEY;
    $data['status'] = CERT_UNREVIEWED;

    return $repository->certCar($data,$city_name);
  }

  public function certTruck(AccountRepository $repository)
  {

    $this->validate($this->request, [
      'name'          => 'required',
      'phone'         => 'required',
      'car_id'        => 'required',
      'd_image'       => 'required',
      'dr_image'      => 'required',
      'truck_size_id' => 'required',
      //ywl
      'city_id'        => 'required',
      'policy_photo'   => 'required',
      'policy_photo_2' => 'required',
    ]);
    $user = $this->getUser();

    if ($cert = $user->certTuck()) {
      if ($cert->status == CERT_FAILED) {
        $cert->driver->delete();
        $cert->driving->delete();
        $cert->delete();
      } else {
        return $this->respondForbidden('你已经提交过货车认证了');
      }
    }
    $data = $this->inputAll();
    $city_name = $data['city_id'];
    $city = City::findByName($data['city_id']);
    if($city){
      $data['city_id'] = $city->id;
    }else{
      return $this->respondForbidden('找不到该城市');
    }
    $data['account_id'] = $user->id;
    $data['type'] = CERT_TRUCK;
    $data['status'] = CERT_UNREVIEWED;

    return $repository->certCar($data,$city_name);
  }

  public function certChauffeur(AccountRepository $repository)
  {

    $this->validate($this->request, [
      'name'      => 'required',
      'phone'     => 'required',
      'id_number' => 'required',
      'city_id'   => 'required',
      'd_image'   => 'required',
    ]);
    $user = $this->getUser();

    if ($cert = $user->certChauffeur()) {
      if ($cert->status == CERT_FAILED) {
        $cert->driver->delete();
        $cert->delete();
      } else {
        return $this->respondForbidden('你已经提交过代驾认证了');
      }
    }
    $data = $this->inputAll();
    $city_name = $data['city_id'];
    $city = City::findByName($data['city_id']);
    if($city){
      $data['city_id'] = $city->id;
    }else{
      return $this->respondForbidden('找不到该城市');
    }
    $data['account_id'] = $user->id;
    $data['type'] = CERT_CHAUFFEUR;
    $data['status'] = CERT_UNREVIEWED;

    return $repository->certChauffeur($data,$city_name);
  }

  public function editShop(AccountRepository $repository)
  {
    return $repository->editShop($this->inputAll());
  }

  public function trips(AccountRepository $repository)
  {
    list($page, $size) = $this->getPageParam($this->request);
    return $repository->trips($page, $size);
  }

  public function unfinishedTrip(LocalTripItem $tripItem)
  {
    $user = $this->getUser();
    $trip = $user->unfinishedTrip();

    if (!$trip) {
      return $this->respondNotFound('当前没有未完成的行程');
    }

    return $this->respondWithItem($trip, $tripItem);
  }


  public function certShop(AccountRepository $repository)
  {

    $this->validate($this->request, [
      'name'      => 'required',
      'shop_name' => 'required',
      'phone'     => 'required',
      's_image'   => 'required',
    ]);
    $user = $this->getUser();

    if ($cert = $user->certShop()) {
      if ($cert->status == CERT_FAILED) {
        $cert->shop->delete();
        $cert->delete();
      } else {
        return $this->respondForbidden('你已经提交过商家认证了');
      }
    }
    $data = $this->inputAll();
    $data['account_id'] = $user->id;
    $data['type'] = CERT_SHOP;
    $data['status'] = CERT_UNREVIEWED;

    return $repository->certShop($data);
  }
}