<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\JourneyPayRuleApply
 *
 * @property integer $id
 * @property integer $user_id 申请人ID
 * @property integer $car_model_id
 * @property integer $city_id 对应城市ID(默认0)
 * @property boolean $limit 最大无里程费公里数
 * @property integer $init_price 起步价, 分
 * @property integer $duration_price 时长费/分钟
 * @property integer $distance_price 里程费/公里
 * @property boolean $status 认证状态
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereCarModelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereLimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereInitPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereDurationPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereDistancePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyPayRuleApply whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JourneyPayRuleApply extends BaseEntity
{
    //
}
