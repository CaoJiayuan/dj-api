<?php

namespace App\Entity;

/**
 * App\Entity\WashOrder
 *
 * @property integer $id
 * @property integer $people_id 商家认证人ID
 * @property string $order_no 交易编号
 * @property integer $account_id
 * @property integer $amount 总价格
 * @property integer $cash_amount 现金支付价格
 * @property integer $credit_amount 积分支付
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\WashOrder whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\WashOrder wherePeopleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\WashOrder whereOrderNo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\WashOrder whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\WashOrder whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\WashOrder whereCashAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\WashOrder whereCreditAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\WashOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\WashOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property boolean $status 订单状态
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\WashOrder whereStatus($value)
 */
class WashOrder extends BaseEntity
{
  protected $fillable = [
    'people_id',
    'order_no',
    'account_id',
    'amount',
    'cash_amount',
    'credit_amount',
    'status',
  ];
  protected $casts = [
    'account_id'    => 'int',
    'amount'        => 'int',
    'cash_amount'   => 'int',
    'credit_amount' => 'int',
    'status'        => 'int',
  ];
}
