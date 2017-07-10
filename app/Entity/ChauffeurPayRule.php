<?php

namespace App\Entity;

/**
 * App\Entity\ChauffeurPayRule
 *
 * @property integer $id
 * @property integer $city_id
 * @property boolean $limit 最大无里程费公里数
 * @property boolean $limit2 最大固定里程费公里数
 * @property integer $distance_fee 固定里程费
 * @property integer $distance_price 超出部分里程费
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRule whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRule whereLimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRule whereLimit2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRule whereDistanceFee($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRule whereDistancePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRule whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $init_price 起步价
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRule whereInitPrice($value)
 */
class ChauffeurPayRule extends BaseEntity
{
  protected $fillable = [
    'city_id',
    'limit',
    'limit',
    'distance_fee',
    'distance_price',
    'init_price'
  ];
 

  /**
   * @return \Illuminate\Database\Eloquent\Collection|ChauffeurPayRule[]
   */
  public static function defaultRules()
  {
    return static::whereCityId(0)->get();
  }
}
