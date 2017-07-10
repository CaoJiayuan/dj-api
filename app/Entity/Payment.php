<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Payment
 *
 * @property integer $id
 * @property boolean $type
 * @property integer $amount
 * @property integer $account_id
 * @property integer $with
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Payment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Payment whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Payment whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Payment whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Payment whereWith($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $cash
 * @property integer $credit
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Payment whereCash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Payment whereCredit($value)
 */
class Payment extends Model
{
    //
}
