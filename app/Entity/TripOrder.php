<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\TripOrder
 *
 * @property integer $id
 * @property integer $trip_id 行程ID
 * @property integer $passenger_id 乘客ID
 * @property integer $driver_id 司机ID
 * @property integer $distance 里程（单位米
 * @property integer $duration 时长（单位分钟）
 * @property integer $start_fee 起步价（单位分）
 * @property integer $distance_fee 里程费（单位米）
 * @property integer $duration_fee 时长费（单位分）
 * @property integer $cash_amount 现金支付价格
 * @property integer $credit_amount 积分支付
 * @property string $cancel_reason 取消原因
 * @property integer $amount 总价格
 * @property integer $status 订单状态(0-已创建,1-已完成,2-已取消)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereTripId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder wherePassengerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereDriverId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereDistance($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereDuration($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereStartFee($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereDistanceFee($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereDurationFee($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereCashAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereCreditAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Entity\Account $passenger
 * @property-read \App\Entity\Account $driver
 * @property-read \App\Entity\Trip $trip
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereCancelReason($value)
 * @property integer $journey_order_id 顺风车订单ID
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereJourneyOrderId($value)
 * @property-read \App\Entity\JourneyOrder $journeyOrder
 * @property-read \App\Entity\Comment $comment
 * @property integer $back_fee 返程费
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder whereBackFee($value)
 * @property integer $pre_pay 预付款
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TripOrder wherePrePay($value)
 */
class TripOrder extends Model
{

  protected $fillable = [
    'trip_id',
    'passenger_id',
    'driver_id',
    'distance',
    'duration',
    'start_fee',
    'distance_fee',
    'duration_fee',
    'cash_amount',
    'credit_amount',
    'amount',
    'status',
    'journey_order_id',
    'pre_pay',
    'back_fee',
  ];

  protected $casts = [
    'trip_id'          => 'int',
    'passenger_id'     => 'int',
    'driver_id'        => 'int',
    'distance'         => 'int',
    'duration'         => 'int',
    'start_fee'        => 'int',
    'distance_fee'     => 'int',
    'duration_fee'     => 'int',
    'cash_amount'      => 'int',
    'credit_amount'    => 'int',
    'amount'           => 'int',
    'status'           => 'int',
    'journey_order_id' => 'int',
    'pre_pay'          => 'int',
    'back_fee'         => 'int',
  ];

  public function passenger()
  {
    return $this->belongsTo(Account::class, 'passenger_id');
  }

  public function journeyOrder()
  {
    return $this->belongsTo(JourneyOrder::class, 'journey_order_id');
  }


  public function trip()
  {
    return $this->belongsTo(Trip::class);
  }

  public function driver()
  {
    return $this->belongsTo(Account::class, 'driver_id');
  }

  public function comment()
  {
    return $this->hasOne(Comment::class);
  }

}
