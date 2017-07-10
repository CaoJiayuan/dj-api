<?php

namespace App\Entity;

/**
 * App\Entity\CreditRecord
 *
 * @property integer $id
 * @property integer $account_id 对应用户id
 * @property integer $changes 积分变化量
 * @property string $cause 积分变化原因
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditRecord whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditRecord whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditRecord whereChanges($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditRecord whereCause($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CreditRecord extends BaseEntity
{

  protected $fillable = ['account_id', 'changes', 'cause'];

  protected $casts = [
    'changes'    => 'int',
    'created_at' => 'timestamp',
  ];
  protected $visible = [
    'changes',
    'cause',
    'created_at',
    'id',
  ];
}
