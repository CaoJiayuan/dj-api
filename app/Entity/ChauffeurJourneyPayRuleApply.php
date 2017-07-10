<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\ChauffeurJourneyPayRuleApply
 *
 * @property integer $id
 * @property integer $user_id 申请人ID
 * @property integer $city_id
 * @property integer $limit 公里数界限
 * @property integer $less_price 低于公里数界限里程价
 * @property integer $more_price 高于公里数界限里程价
 * @property integer $less_price_back 低于公里数界限返程价
 * @property integer $more_price_back 高于公里数界限返程价
 * @property boolean $status 认证状态
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereLimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereLessPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereMorePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereLessPriceBack($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereMorePriceBack($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurJourneyPayRuleApply whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChauffeurJourneyPayRuleApply extends Model
{
    //
}
