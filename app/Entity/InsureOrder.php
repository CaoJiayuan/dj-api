<?php

namespace App\Entity;

/**
 * App\Entity\InsureOrder
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $insure_id 保险商家ID
 * @property string $insurances 险种, 以;隔开
 * @property string $dr_image 行驶证照片
 * @property string $car_id 车牌号码
 * @property boolean $handled 是否处理
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereInsureId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereInsurances($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereDrImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereCarId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereHandled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $amount 报价
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereAmount($value)
 * @property string $order_no
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereOrderNo($value)
 * @property string $address
 * @property-read \App\Entity\Account $account
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder whereAddress($value)
 * @property integer $pay_amount
 * @property-read \App\Entity\Insure $insure
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsureOrder wherePayAmount($value)
 */
class InsureOrder extends BaseEntity
{

  protected $casts = [
    'handled' => 'int',
    'amount' => 'int'
  ];
  
  protected $fillable = [
    'account_id',
    'insure_id',
    'insurances',
    'dr_image',
    'car_id',
    'handled',
    'amount',
    'order_no',
    'address',
    'pay_amount'
  ];
  public $cantCopy = [
    'handled',
    'amount',
  ];

  public function insure()
  {
    return $this->belongsTo(Insure::class);
  }

  public function account()
  {
    return $this->belongsTo(Account::class);
  }
}
