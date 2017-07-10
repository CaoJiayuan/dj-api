<?php

namespace App\Entity;

/**
 * App\Entity\PlatformPay
 *
 * @property-read mixed $created_at
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $driver_id
 * @property integer $trip_id
 * @property integer $amount
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\PlatformPay whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\PlatformPay whereDriverId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\PlatformPay whereTripId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\PlatformPay whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\PlatformPay whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\PlatformPay whereUpdatedAt($value)
 */
class PlatformPay extends BaseEntity
{
  protected $fillable = [
    'driver_id',
    'trip_id',
    'amount',
  ];
}
