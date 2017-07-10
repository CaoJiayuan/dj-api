<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\ChauffeurPayRuleApply
 *
 * @property integer $id
 * @property integer $user_id 申请人ID
 * @property integer $city_id
 * @property boolean $limit 最大无里程费公里数
 * @property boolean $limit2 最大固定里程费公里数
 * @property integer $distance_fee 固定里程费
 * @property integer $distance_price 超出部分里程费
 * @property boolean $status 认证状态
 * @property integer $init_price 起步价
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereLimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereLimit2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereDistanceFee($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereDistancePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereInitPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChauffeurPayRuleApply whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChauffeurPayRuleApply extends Model
{
    //
}
