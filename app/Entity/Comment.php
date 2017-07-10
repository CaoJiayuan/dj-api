<?php

namespace App\Entity;


/**
 * App\Entity\Comment
 *
 * @property integer $id
 * @property boolean $score 评分
 * @property boolean $type 订单类型
 * @property integer $trip_order_id 评论的订单id
 * @property integer $account_id 对应用户id
 * @property string $comment 评论内容
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereScore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereTripOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Comment extends BaseEntity
{
  protected $fillable = ['score','type', 'account_id', 'comment', 'trip_order_id'];

}
