<?php
/**
 * AccountRepository.php
 * Date: 16/7/21
 * Time: 下午1:36
 * Created by Caojiayuan
 */

namespace App\Repositories;


use Api\StarterKit\Utils\ApiResponse;
use App\Entity\Account;
use App\Entity\City;
use App\Entity\DriverLicense;
use App\Entity\DrivingLicense;
use App\Entity\Person;
use App\Entity\Shop;
use App\Entity\Trip;
use App\Entity\Withdraw;
use App\Traits\Authenticated;
use App\Traits\ModelHelper;
use App\Traits\OneDeviceAccess;
use App\Traits\Payment;
use App\Utils\Arr;
use Illuminate\Database\Eloquent\Model;

class AccountRepository extends Repository
{
  use ModelHelper, Authenticated, ApiResponse, Payment, OneDeviceAccess;

  /**
   * @return Model
   */
  public function getModel()
  {
    return Account::class;
  }

  public function edit($data)
  {
    if (isset($data['city'])) {
      $city = City::findByName($data['city']);
      if ($city) {
        $data['city_id'] = $city->id;
      }
    }

    $user = $this->getUser();
    if ($channelId = arr_get($data, 'channel_id')) {
      $this->checkOneDevice($user, $channelId);
    }

    $account = $this->copy($user, $data);

    $account->token = $this->getToken();

    $account->city;

    return $account;
  }

  public function trips($page = 1, $size = 20)
  {
    $user = $this->getUser();

    $relation = $user->trips();

    $relation->leftJoin('truck_sizes', 'truck_sizes.id', '=', 'truck_size_id');
    $relation->leftJoin('truck_types', 'truck_types.id', '=', 'truck_type_id');
    $relation->select([
      'trips.id',
      'trips.time',
      'trips.time_end',
      'type',
      'start',
      'destination',
      'status',
      'trips.created_at',
      'width',
      'height',
      'length',
      'truck_types.name as truck_name',
      \DB::raw('CASE WHEN role=1 THEN true ELSE false END as is_driver'),
    ]);
    if (defined('ROLE_TYPE')) {
      if (ROLE_TYPE == ROLE_DRIVER) {
        $relation->where('trips.role', '=', ROLE_DRIVER);
      } else {
        $relation->where('trips.role', '=', ROLE_PASSENGER);
        return $relation->orderBy('trips.status')->getResults()->forPage($page, $size)->toArray();
      }

    }


    $result1 = $relation->getResults()->toArray();

    $builder = Trip::rightJoin('trip_orders', 'trips.id', '=', 'trip_orders.trip_id');
    $builder->leftJoin('truck_sizes', 'truck_sizes.id', '=', 'truck_size_id');
    $builder->leftJoin('truck_types', 'truck_types.id', '=', 'truck_type_id');
    $builder->where('trip_orders.driver_id', '=', $user->id);

    $builder->orderBy('trips.status','asc')->orderBy('trips.created_at', 'desc');

    $builder->select([
      'trips.id',
      'trips.time',
      'trips.time_end',
      'trips.type',
      'trips.start',
      'trips.destination',
      'trips.status',
      'trips.created_at',
      'trip_orders.driver_id',
      'width',
      'height',
      'length',
      'truck_types.name as truck_name',
      \DB::raw('true as is_driver'),
    ]);

    $result2 = $builder->get()->toArray();
    $result = array_merge($result1, $result2);
    $result = Arr::sort($result, 'status');
    $data = array_for_page($result, $page, $size);

    return $data;
  }

  public function certCar($data,$city_name)
  {
    return \DB::transaction(function () use ($data,$city_name) {
      $person = $this->copy(Person::class, $data);
      $data['people_id'] = $person->id;
      $driver = $this->copy(DriverLicense::class, $data);
      /** @var DrivingLicense $driving */
      $driving = $this->copy(DrivingLicense::class, $data);
      $driving['city_id'] = $city_name;
      $person['car_model'] = $driving->carModel()->type;
      $keys = [
        'people_id' => 'id',
        'name',
        'phone',
        'car_id',
        'city_id',
        'd_image',
        'dr_image',
        'policy_photo',
        'policy_photo_2',
        'status',
        'car_model'
      ];

      $type = arr_get($data, 'type', 0);
      if ($type == CERT_CAR) {
        $keys['car_model.name'] = 'car_type';
      }

      if ($type == CERT_TRUCK) {
        $keys[] = 'size';
        $keys[] = 'truck_name';
        if ($s = $driving->size) {
          $driving['truck_name'] = $s->name;
        }
      }

      return array_get_values($keys, $driver ,$driving, $person);
    });
  }

  public function certJourney($data,$city_name)
  {
    return \DB::transaction(function () use ($data,$city_name) {
      $person = $this->copy(Person::class, $data);
      $data['people_id'] = $person->id;
      $driver = $this->copy(DriverLicense::class, $data);
      $driving = $this->copy(DrivingLicense::class, $data);
      $driving['city_id'] = $city_name;
      return array_get_values([
        'people_id' => 'id',
        'name',
        'phone',
        'car_id',
        'city_id',
        'd_image',
        'dr_image',
        'policy_photo',
        'policy_photo_2',
        'status',
        'car_model'
      ], $driver, $driving, $person);
    });
  }

  public function certChauffeur($data,$city_name)
  {
    return \DB::transaction(function () use ($data,$city_name) {
      $person = $this->copy(Person::class, $data);
      $data['people_id'] = $person->id;
      $driver = $this->copy(DriverLicense::class, $data);
      $driver['city_id'] = $city_name;

      return array_get_values([
        'people_id' => 'id',
        'name',
        'phone',
        'id_number',
        'd_image',
        'city_id',
        'status',
      ], $driver, $person);
    });
  }

  public function certShop($data)
  {
    return \DB::transaction(function () use ($data) {
      $person = $this->copy(Person::class, $data);
      $data['people_id'] = $person->id;
      $shop = $this->copy(Shop::class, $data);

      return array_get_values([
        'people_id' => 'id',
        'name',
        'shop_name',
        'phone',
        'status',
        's_image',
      ], $shop, $person);
    });
  }

  public function withDraw($data)
  {
    return \DB::transaction(function () use ($data) {
      $user = $this->getUser();
      if ($user->balance < WITHDRAW_LIMIT) {
        return $this->respondForbidden('用户账户金额不能小于' . (WITHDRAW_LIMIT / 100) . '元');
      }

      if($data['amount'] < WITHDRAW_LIMIT_1)
      {
        return $this->respondForbidden('每次提现金额不得少于' . (WITHDRAW_LIMIT_1 / 100) . '元');
      }

      $data['account_id'] = $user->id;
      $withDraw = $this->copy(Withdraw::class, $data);
      $this->changeBalance($user, -$data['amount'], '提现');

      return $withDraw;
    });
  }

  public function editShop($data)
  {
    $user = $this->getUser();

    $cert = $user->certShop();

    if (!$cert) {
      return $this->respondForbidden('你还没有提交商家申请');
    }

    return \DB::transaction(function () use ($data, $cert) {
      $this->forceCopy = true;
      $data['status'] = CERT_UNREVIEWED;
      $this->copy($cert, $data);
      $shop = $this->copy($cert->shop, $data);

      return array_get_values([
        'people_id' => 'id',
        'name',
        'shop_name',
        'phone',
        'status',
        's_image',
      ], $shop, $cert);
    });
  }

  public function washOrders($page = 1, $size = 20)
  {

  }
}