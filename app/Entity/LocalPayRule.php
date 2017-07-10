<?php

namespace App\Entity;

/**
 * App\Entity\LocalPayRule
 *
 * @property integer $id
 * @property integer $car_model_id
 * @property integer $init_price 起步价, 分
 * @property integer $duration_price 时长费/分钟
 * @property integer $distance_price 里程费/公里
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRule whereCarModelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRule whereInitPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRule whereDurationPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRule whereDistancePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRule whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $city_id 对应城市ID(默认0)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRule whereCityId($value)
 * @property-read \App\Entity\CarModel $car
 * @property boolean $limit 最大无里程费公里数
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRule whereLimit($value)
 */
class LocalPayRule extends BaseEntity
{

  protected $casts = [
    'city_id'        => 'int',
    'car_model_id'   => 'int',
    'init_price'     => 'int',
    'duration_price' => 'int',
    'distance_price' => 'int',
  ];
  protected $fillable = [
    'city_id',
    'car_model_id',
    'init_price',
    'duration_price',
    'distance_price',
    'limit',
  ];

  public function car()
  {
    return $this->belongsTo(CarModel::class, 'car_model_id');
  }

  public static function defaultRules()
  {
    $c = new City();
    $c->id = 0;

    return $c->localPayRule;
  }
}
