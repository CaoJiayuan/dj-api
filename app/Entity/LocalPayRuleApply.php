<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\LocalPayRuleApply
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $user_id 申请人ID
 * @property integer $car_model_id
 * @property integer $city_id 对应城市ID(默认0)
 * @property integer $init_price 起步价, 分
 * @property integer $duration_price 时长费/分钟
 * @property integer $distance_price 里程费/公里
 * @property boolean $status 认证状态
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereCarModelId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereInitPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereDurationPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereDistancePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereUpdatedAt($value)
 * @property boolean $limit 最大无里程费公里数
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\LocalPayRuleApply whereLimit($value)
 */
class LocalPayRuleApply extends Model
{
    //
}
