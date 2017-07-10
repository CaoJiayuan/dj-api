<?php

namespace App\Entity;

use App\Vendor\Illuminate\RelationEmpty;
use App\Vendor\Illuminate\RelationNull;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Entity\Trip
 *
 * @property integer $id
 * @property integer $account_id 对应用户id
 * @property string $start 起点
 * @property integer $city_id 起点城市id
 * @property string $destination 终点
 * @property string $time 出发时间
 * @property float $longitude 经度
 * @property float $latitude 纬度
 * @property float $longitude2 目的地经度
 * @property float $latitude2 目的地纬度
 * @property boolean $population 同行人数
 * @property boolean $rest_sets 剩余空位
 * @property boolean $status 行程状态
 * @property boolean $type 行程类型
 * @property boolean $role 角色类型
 * @property boolean $car_model 车类型
 * @property integer $trip_fee 预估费用
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereStart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereDestination($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereLongitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereLatitude($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereLongitude2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereLatitude2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip wherePopulation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereRestSets($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereCarModel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Entity\Account $account
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereTripFee($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TripOrder[] $orders
 * @property string $start_at 行程开始时间
 * @property string $finish_at 行程结束时间
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereStartAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereFinishAt($value)
 * @property-read \App\Entity\City $city
 * @property integer $truck_size_id 货车型号ID
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereTruckSizeId($value)
 * @property-read \App\Entity\TripOrder $order
 * @property-read \App\Entity\Trip[] $related
 * @property-read \App\Entity\Trip[]|Collection $relatedWithoutSelf
 * @property-read \App\Entity\Account $driver
 * @property-read \App\Entity\Account $passenger
 * @property string $time_end 预定结束时间
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereTimeEnd($value)
 * @property boolean $pool
 * @property-read \App\Entity\TruckSize $truck
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip wherePool($value)
 * @property integer $distance 里程（单位米
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Trip whereDistance($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\PlatformPay[] $platformPay
 */
class Trip extends BaseEntity
{

  protected $casts = [
    'trip_fee'      => 'int',
    'latitude'      => 'float',
    'longitude2'    => 'float',
    'longitude'     => 'float',
    'latitude2'     => 'float',
    'type'          => 'int',
    'role'          => 'int',
    'status'        => 'int',
    'truck_size_id' => 'int',
    'dis'           => 'float',
    'start_at'      => 'timestamp',
    'time'          => 'timestamp',
    'time_end'      => 'timestamp',
    'finish_at'     => 'timestamp',
    'population'    => 'timestamp',
    'width'         => 'int',
    'height'        => 'int',
    'length'        => 'int',
    'pool'          => 'bool',
    'is_driver'     => 'bool',
    'sex'           => 'int',
    'city_id'       => 'int',
    'distance'      => 'int',
  ];

  protected $fillable = [
    'account_id',
    'start',
    'city_id',
    'destination',
    'time',
    'longitude',
    'latitude',
    'longitude2',
    'latitude2',
    'population',
    'rest_sets',
    'status',
    'type',
    'role',
    'car_model',
    'trip_fee',
    'start_at',
    'finish_at',
    'truck_size_id',
    'time_end',
    'pool',
    'distance',
  ];
  protected $hidden = [
    'ehours',
    'shours'
  ];

  public $cantCopy = [
    'status',
  ];

  public function account()
  {
    return $this->belongsTo(Account::class);
  }

  public function orders()
  {
    return $this->hasMany(TripOrder::class);
  }

  public function city()
  {
    return $this->belongsTo(City::class);
  }

  /**
   * @return Builder|$this
   */
  public function published()
  {
    return $this->where('trips.status', '=', TRIP_PUBLISHED);
  }

  public function near($lng, $lat, $type, $page = 1, $limit = 20)
  {

  }

  public function isFinished()
  {
    return $this->status > TRIP_ACTIVE && $this->status != TRIP_CANCELED;
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne|Builder
   */
  public function order()
  {
    return $this->hasOne(TripOrder::class);
  }

  public function driver()
  {
    foreach ($this->orders as $order) {
      return $order->driver();
    }

    return new RelationNull($this);
  }

  public function passenger()
  {
    foreach ($this->orders as $order) {
      return $order->passenger();
    }

    return new RelationNull($this);
  }

  /**
   * @return RelationEmpty|\Illuminate\Database\Eloquent\Relations\BelongsToMany|Builder
   */
  public function related()
  {
    if ($order = $this->order) {
      $o = $order->journeyOrder;
      if ($o) {
        return $o->trips();
      }
    }

    return new RelationEmpty($this);
  }

  public function relatedWithoutSelf()
  {
    $relation = $this->related();
    $relation->where('trips.id', '!=', $this->id);
    $relation->where('trips.status', '<', TRIP_FINISHED);

    return $relation;
  }

  public function score()
  {
    $re = $this->order();
    $re->with('comment');

    return (int)array_get($re->first(), 'comment.score', 0);
  }

  public function truck()
  {
    $relation = $this->belongsTo(TruckSize::class, 'truck_size_id');

    $relation->with('type');

    return $relation;
  }

  /**
   * @return string
   */
  public function getReadableType()
  {
    $map = [
      TYPE_LOCAL             => '市内快车',
      TYPE_JOURNEY           => '长途约车',
      TYPE_JOURNEY_ONLY      => '长途包车',
      TYPE_JOURNEY_SPECIAL   => '长途专线',
      TYPE_CHAUFFEUR         => '酒后代驾',
      TYPE_CHAUFFEUR_JOURNEY => '长途代驾',
      TYPE_TRUCK             => '货车',
    ];

    return $map[$this->type];
  }

  public function platformPay()
  {
    return $this->hasMany(PlatformPay::class,'trip_id');
  }
}
