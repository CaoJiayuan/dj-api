<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\BalanceRecord
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $account_id 对应用户id
 * @property integer $changes 余额变化量
 * @property string $cause 余额变化原因
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\BalanceRecord whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\BalanceRecord whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\BalanceRecord whereChanges($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\BalanceRecord whereCause($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\BalanceRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\BalanceRecord whereUpdatedAt($value)
 */
class BalanceRecord extends Model
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
