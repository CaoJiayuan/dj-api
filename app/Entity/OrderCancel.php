<?php

namespace App\Entity;

/**
 * App\Entity\OrderCancel
 *
 * @property integer $id
 * @property integer $account_id
 * @property boolean $type
 * @property boolean $role
 * @property integer $order_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\OrderCancel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\OrderCancel whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\OrderCancel whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\OrderCancel whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\OrderCancel whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\OrderCancel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\OrderCancel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderCancel extends BaseEntity
{
  protected $fillable = [
    'account_id',
    'type',
    'role',
    'order_id',
  ];

  protected $casts = [
    "account_id" => "int",
    "type"       => "int",
    "role"       => "int",
    "order_id"   => "int",
  ];
}
