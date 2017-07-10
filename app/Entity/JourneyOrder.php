<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

/**
 * App\Entity\JourneyOrder
 * 顺风车订单
 *
 * @property integer $id
 * @property integer $account_id 司机ID
 * @property integer $amount 总价
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyOrder whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyOrder whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyOrder whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property boolean $status 订单状态
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TripOrder[] $orders
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\JourneyOrder whereStatus($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Trip[] $trips
 */
class JourneyOrder extends Model
{
  protected $fillable = [
    'account_id',
    'amount',
    'status',
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany|Builder
   */
  public function orders()
  {
    return $this->hasMany(TripOrder::class, 'journey_order_id');
  }

  public function trips()
  {
    return $this->belongsToMany(Trip::class, 'trip_orders');
  }

  public function populations()
  {
    $t = Trip::rightJoin('trip_orders', function ($builder) {
      /** @var JoinClause $builder */
      $builder->on('trip_orders.trip_id', '=', 'trips.id');
    })->where('trips.status', '<', TRIP_FINISHED)
    ->where('trip_orders.journey_order_id', '=', $this->id)->sum('population');

    return $t;
  }
}
