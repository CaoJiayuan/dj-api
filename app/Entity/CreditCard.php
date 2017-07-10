<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\CreditCard
 *
 * @property integer $id
 * @property string $username
 * @property integer $account_id
 * @property string $name
 * @property string $card_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditCard whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditCard whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditCard whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditCard whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditCard whereCardId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CreditCard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CreditCard extends Model
{
  protected $fillable = [
    'username',
    'account_id',
    'name',
    'card_id'
  ];

  protected $visible = [
    'name','id','card_id'
  ];
}
