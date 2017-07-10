<?php

namespace App\Entity;

use App\Vendor\Illuminate\RelationNull;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Entity\Account
 *
 * @property integer $id
 * @property string $username 手机号
 * @property string $nickname 昵称
 * @property string $password 密码
 * @property string $avatar 头像
 * @property string $channel_id 设备channel id
 * @property integer $city_id 城市id
 * @property boolean $sex 用户性别
 * @property boolean $receivable 司机可否接受推送
 * @property string $receive_at 司机接受推送的时间
 * @property integer $credits 积分
 * @property boolean $device 设备类型
 * @property string $remember_token
 * @property string $token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\CreditRecord[] $creditsData
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Comment[] $comments
 * @property-read \App\Entity\Shop $shop
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereChannelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereReceivable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereReceiveAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereCredits($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereDevice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Trip[] $trips
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Person[] $persons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\CreditCard[] $creditCards
 * @property integer $balance 余额(分)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereBalance($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\BalanceRecord[] $balanceData
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\JourneyOrder[] $journeyOrders
 * @property boolean $working 是否上班
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Account whereWorking($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\InsureOrder[] $insureOrder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\OrderCancel[] $orderCanceled
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\WashOrder[] $washOrders
 */
class Account extends Authenticatable
{

  protected $fillable = [
    'username',
    'password',
    'channel_id',
    'avatar',
    'receivable',
    'nickname',
    'receive_at',
    'device',
    'credits',
    'city_id',
    'login_id',
    'sex'
  ];

  protected $hidden = [
    'password',
    'remember_token',
    'created_at',
    'updated_at',
  ];

  protected $casts = [
    'sex'        => 'int',
    'credits'    => 'int',
    'balance'    => 'int',
    'device'     => 'int',
    'receive_at' => 'timestamp',
    'receivable' => 'bool',
    'working'    => 'bool',
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany|Builder
   */
  public function creditsData()
  {
    $relation = $this->hasMany(CreditRecord::class);

    $relation->orderBy('id','desc');
    return $relation;
  }

  public function balanceData()
  {

    $relation = $this->hasMany(BalanceRecord::class);
    $relation->orderBy('id','desc');

    return $relation;
  }


  public function comments()
  {
    return $this->hasMany(Comment::class);
  }

  public function score()
  {
    $score = $this->comments->average('score');

    return $score ? round($score, 1) : 0;
  }


  public function creditCards()
  {
    return $this->hasMany(CreditCard::class);
  }

  public function shop()
  {
    $person = $this->certShop();
    if (!$person) {
      return new RelationNull($this);
    }

    return $person->shop();
  }


  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany|Builder
   */
  public function trips()
  {
    $relation = $this->hasMany(Trip::class);


    return $relation;
  }

  /**
   * @param null $type
   * @param null $role
   * @return Trip|null
   */
  public function unfinishedTrip($type = null, $role = null)
  {

    /** @var Trip $result */
    $result = null;
    $relation = $this->trips();
    $relation->where('status', '<', TRIP_FINISHED);
    if ($type !== null) {
      $relation->where('type', '=', $type);
    }
    if ($role !== null) {
      $relation->where('role', '=', $role);
    }
    
    if ($trip = $relation->first()) {
      $result = $trip;
    } else {
      $t = Trip::rightJoin('trip_orders', 'trip_orders.trip_id', '=', 'trips.id')
        ->where('trips.status', '<', TRIP_FINISHED)
        ->where(function ($builder) {
          /** @var Builder $builder */
          $builder->orWhere('trip_orders.passenger_id', '=', $this->id)
            ->orWhere('trip_orders.driver_id', '=', $this->id);
        });

      if ($type !== null) {
        $t->where('trips.type', '=', $type);
      }
      if ($role !== null) {
        $t->where('trips.role', '=', $role);
      }

      $t->select([
        'trips.*',
      ]);
      $result = $t->first();
    }

    if ($result) {
     if (in_array($result->type, [TYPE_LOCAL, TYPE_CHAUFFEUR])) {
       if ($result->status == TRIP_PUBLISHED && (time() - $result->created_at > LOCAL_TRIP_TIME_LIMIT * 60)) {
         $result->status = TRIP_CANCELED;
         $result->save();
         return $this->unfinishedTrip();
       }
     }
    }
    
    return $result;
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany|Builder
   */
  public function persons()
  {
    $relation = $this->hasMany(Person::class);


    return $relation;
  }

  /**
   * @return Person
   */
  public function certCar()
  {
    return $this->cert(CERT_CAR);
  }

  /**
   * @return Person
   */
  public function certTuck()
  {

    return $this->cert(CERT_TRUCK);
  }

  /**
   * @param $type
   * @return Person
   */
  public function cert($type)
  {
    $relation = $this->persons();
    $relation->where('type', '=', $type);
    $first = $relation->first();

    $cities = City::all();

    if ($first) {
      if ($type == CERT_CAR || $type == CERT_TRUCK || $type == CERT_JOURNEY) {
        $first->driver;
        $city = $cities->whereLoose('id',$first->driver->city_id);
        if($city->count()>0)
        {
          $first->driver['city_id'] = $city->first()->name;
        }else{
          $first->driver['city_id'] = '成都市';
        }
        $first->driving;
        $city = $cities->whereLoose('id',$first->driving->city_id);
        if($city->count()>0)
        {
          $first->driving['city_id'] = $city->first()->name;
        }else{
          $first->driving['city_id'] = '成都市';
        }
      } else {
        if ($type == CERT_SHOP) {
          $first->shop;
        } else {
          if ($type == CERT_CHAUFFEUR) {
            $first->driver;
            $city = $cities->whereLoose('id',$first->driver->city_id);
            if($city->count()>0)
            {
              $first->driver['city_id'] = $city->first()->name;
            }else{
              $first->driver['city_id'] = '成都市';
            }
          }
        }
      }
    }

    return $first;
  }

  /**
   * @return Person
   */
  public function certChauffeur()
  {
    return $this->cert(CERT_CHAUFFEUR);
  }

  public function certJourney()
  {
    return $this->cert(CERT_JOURNEY);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany|Builder
   */
  public function journeyOrders()
  {
    $relation = $this->hasMany(JourneyOrder::class);

    $relation->orderBy('id','desc');
    return $relation;
  }

  /**
   * @return JourneyOrder|null
   */
  public function journeyOrderUnfinished()
  {
    $builder = JourneyOrder::leftJoin('accounts', 'accounts.id', '=','journey_orders.account_id');
    $builder->rightJoin('trip_orders','journey_order_id','=','journey_orders.id');
    $builder->leftJoin('trips','trip_orders.trip_id','=','trips.id');
    $builder->where('journey_orders.account_id','=', $this->id);
    $builder->where('trips.status','<', TRIP_FINISHED);
    $builder->select(['journey_orders.*','trips.type','trips.car_model']);
    $order = $builder->first();
    return $order;
  }

  /**
   * @return JourneyOrderCount|null
   */
  public function journeyOrderUnfinishedCount()
  {
    $builder = JourneyOrder::leftJoin('accounts', 'accounts.id', '=','journey_orders.account_id');
    $builder->rightJoin('trip_orders','journey_order_id','=','journey_orders.id');
    $builder->leftJoin('trips','trip_orders.trip_id','=','trips.id');
    $builder->where('journey_orders.account_id','=', $this->id);
    $builder->where('trips.status','<', TRIP_FINISHED);
    $builder->select(['journey_orders.*']);
    $order = $builder->count();
    return $order;
  }

  /**
   * @return Person
   */
  public function certShop()
  {
    return $this->cert(CERT_SHOP);
  }

  /**
   * @param null $type
   * @param null $role
   * @param int $page
   * @param int $size
   * @param array $columns
   * @return Trip[]|Collection
   */
  public function getTrips($type = null, $role = null, $page = 1, $size = 20, array $columns = [])
  {
    $relation = $this->trips();
    if ($type !== null) {
      $relation->where('type', '=', $type);
    }
    if ($role !== null) {
      $relation->where('role', '=', $role);
    }

    if (!empty($columns)) {
      $relation->select($columns);
    }

    $relation->forPage($page, $size);

    return $relation->getResults();
  }

  public function insureOrder()
  {
    return $this->hasMany(InsureOrder::class);
  }

  /**
   * @param $type
   * @return Collection
   */
  public function passengerCanceledToday($type)
  {
    return $this->orderCanceledToday($type, ROLE_PASSENGER);
  }

  /**
   * @param $type
   * @return Collection
   */
  public function driverCanceledToday($type)
  {
    return $this->orderCanceledToday($type, ROLE_DRIVER);
  }

  /**
   * @return Collection|Withdraw[]
   */
  public function withdrawToday()
  {
    $w = Withdraw::whereAccountId($this->id);
    $w->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);

    return $w->get();
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany|Builder
   */
  public function orderCanceled()
  {
    return $this->hasMany(OrderCancel::class);
  }

  /**
   * @return int
   */
  public function incomeCredit()
  {
    $r = $this->creditsData();

    $r->where('changes', '>', 0);
    
    return $r->sum('changes');
  }

  /**
   * @param $type
   * @param $role
   * @return Collection
   */
  public function orderCanceledToday($type, $role)
  {
    $c = $this->orderCanceled();
    $c->where('type', '=', $type);
    $c->where('role', '=', $role);
    $c->whereBetween('created_at', [Carbon::today(), Carbon::tomorrow()]);

    return $c->get();
  }

  public function unpayedTripPassenger($type = null)
  {
    $relation = $this->trips();

    $relation->where('status', '=', TRIP_FINISHED);

    if ($type !== null) {
      $relation->where('type', '=', $type);
    }

    if ($trip = $relation->first()) {

      return $trip;
    }

    $t = Trip::rightJoin('trip_orders', 'trip_orders.trip_id', '=', 'trips.id')
      ->where('trips.status', '=', TRIP_FINISHED)
      ->where('trip_orders.passenger_id', '=', $this->id);

    if ($type !== null) {
      $t->where('trips.type', '=', $type);
    }

    $t->select([
      'trips.id',
    ]);

    $tr = $t->first();

    if ($tr) {
      return Trip::find($tr->id);
    }

    return null;
  }


  /**
   * @param null $type
   * @return Collection|static[]
   */
  public function unpayedTripsDriver($type = null)
  {
    $t = Trip::rightJoin('trip_orders', 'trip_orders.trip_id', '=', 'trips.id')
      ->where('trips.status', '=', TRIP_FINISHED)
      ->where('trip_orders.driver_id', '=', $this->id);

    if ($type !== null) {
      $t->where('trips.type', '=', $type);
    }

    $t->select([
      'trips.id',
    ]);

    $tr = $t->get();

    return Trip::whereIn('id', $tr)->get();
  }


  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany|Builder
   */
  public function washOrders()
  {
    return $this->hasMany(WashOrder::class);
  }

  public function tripIncomeThisMonth()
  {
    
  }
}
