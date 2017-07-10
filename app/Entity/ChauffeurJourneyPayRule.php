<?php

namespace App\Entity;

/**
 * App\Entity\ChauffeurJourneyPayRule
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $limit 公里数界限
 * @property integer $less_price 低于公里数界限里程价
 * @property integer $more_price 高于公里数界限里程价
 * @property integer $less_price_back 低于公里数界限返程价
 * @property integer $more_price_back 高于公里数界限返程价
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRule whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRule whereLimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRule whereLessPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRule whereMorePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRule whereLessPriceBack($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRule whereMorePriceBack($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChauffeurJourneyPayRule extends BaseEntity
{
  protected $fillable = [
    'city_id',
    'limit',
    'less_price',
    'more_price',
    'less_price_back',
    'more_price_back',
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Collection|ChauffeurJourneyPayRule[]
   */
  public static function defaultRules()
  {
    return static::whereCityId(0)->get();
  }
}
