<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\TruckPayRuleApply
 *
 * @property integer $id
 * @property integer $user_id 申请人ID
 * @property integer $city_id 对应城市ID(默认0)
 * @property integer $truck_size_id 对应货车尺寸ID
 * @property integer $init_price 起步价, 分
 * @property integer $distance_price 里程费/公里
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRuleApply whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRuleApply whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRuleApply whereCityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRuleApply whereTruckSizeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRuleApply whereInitPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckPayRuleApply whereDistancePrice($value)
 * @mixin \Eloquent
 */
class TruckPayRuleApply extends Model
{
    //
}
