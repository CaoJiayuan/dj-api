<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Complain
 *
 * @property integer $id
 * @property integer $driver_id 司机ID
 * @property integer $account_id 投诉人ID
 * @property string $content 投诉内容
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complain whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complain whereDriverId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complain whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complain whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complain whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complain whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Complain extends Model
{
  protected $fillable = [
    'account_id',
    'driver_id',
    'content',
  ];
}
