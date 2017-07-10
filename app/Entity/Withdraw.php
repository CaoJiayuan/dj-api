<?php

namespace App\Entity;

/**
 * App\Entity\Withdraw
 *
 * @property integer $id
 * @property integer $account_id 账户ID
 * @property integer $amount 金额
 * @property integer $credit_card_id 银行卡ID
 * @property boolean $status 提现状态
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Withdraw whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Withdraw whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Withdraw whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Withdraw whereCreditCardId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Withdraw whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Withdraw whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Withdraw whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $withdrawer 提现操作人
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Withdraw whereWithdrawer($value)
 */
class Withdraw extends BaseEntity
{
  protected $fillable = [
    'account_id',
    'amount',
    'credit_card_id',
    'status',
    'withdrawer'
  ];

  protected $casts = [
    'status' => 'int',
  ];
  public $cantCopy = [
    'status',
  ];
}
