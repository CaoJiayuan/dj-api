<?php

namespace App\Entity;

/**
 * App\Entity\TruckPayRule
 *
 * @property integer $id
 * @property integer $truck_size_id 对应货车尺寸ID
 * @property integer $city_id 对应城市ID(默认0)
 * @property integer $init_price 起步价, 分
 * @property integer $distance_price 里程费/公里
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRule whereTruckSizeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRule whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRule whereInitPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRule whereDistancePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRule whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Entity\TruckSize $size
 */
class TruckPayRule extends BaseEntity
{
  protected $fillable = [
    'city_id',
    'truck_size_id',
    'init_price',
    'distance_price',
  ];
  protected $casts = [
    'truck_size_id'  => 'int',
    'city_id'        => 'int',
    'init_price'     => 'int',
    'distance_price' => 'int',
  ];

  public static function defaultRules()
  {
    $c = new City();
    $c->id = 0;

    return $c->truckPayRule;
  }

  public function size()
  {
    return $this->belongsTo(TruckSize::class,'truck_size_id');
  }
}
