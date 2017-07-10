<?php

namespace App\Entity;

/**
 * App\Entity\JourneyPayRule
 *
 * @property integer $id
 * @property integer $car_model_id
 * @property integer $city_id 对应城市ID(默认0)
 * @property boolean $limit 最大无里程费公里数
 * @property integer $init_price 起步价, 分
 * @property integer $duration_price 时长费/分钟
 * @property integer $distance_price 里程费/公里
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRule whereCarModelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRule whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRule whereLimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRule whereInitPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRule whereDurationPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRule whereDistancePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRule whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Entity\CarModel $car
 */
class JourneyPayRule extends BaseEntity
{
  protected $fillable = [
    'car_model_id',
    'city_id',
    'limit',
    'init_price',
    'duration_price',
    'distance_price',
    'limit2',
    'less_price',
    'type'
  ];

  public function car()
  {
    return $this->belongsTo(CarModel::class, 'car_model_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Collection|JourneyPayRule[]
   */
  public static function defaultRules()
  {
    return static::with('car')->whereCityId(0)->get();
  }
}
