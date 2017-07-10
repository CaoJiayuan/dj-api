<?php

namespace App\Entity;


/**
 * App\Entity\Person
 *
 * @property integer $id
 * @property string $id_number 身份证号
 * @property string $name 真实姓名
 * @property string $phone 联系方式
 * @property integer $account_id 对应用户id
 * @property boolean $type 认证类型
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Entity\Account $account
 * @property-read \App\Entity\Shop $shop
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person whereIdNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $status 认证状态
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person whereStatus($value)
 * @property-read \App\Entity\DriverLicense $driver
 * @property-read \App\Entity\DrivingLicense $driving
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TraderApply[] $trader
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TraderApply[] $firstTrader
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TraderApply[] $secondTrader
 * @property boolean $profitable 是否可获取补贴
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person whereProfitable($value)
 * @property string $reviewed_at 审核通过时间
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Person whereReviewedAt($value)
 */
class Person extends BaseEntity
{
  protected $fillable = ['name', 'phone', 'type', 'account_id', 'id_number', 'status', 'profitable'];

  protected $casts = [
    'account_id'  => 'int',
    'type'        => 'int',
    'status'      => 'int',
    'reviewed_at' => 'timestamp',
  ];

  public $cantCopy = [
    'status',
  ];

  public function account()
  {
    return $this->belongsTo(Account::class);
  }

  public function shop()
  {
    $relation = $this->hasOne(Shop::class, 'people_id');

    return $relation;
  }

  public function driver()
  {
    $relation = $this->hasOne(DriverLicense::class, 'people_id');

    $relation->where('type', '=', $this->type);

    return $relation;
  }

  public function driving()
  {
    $relation = $this->hasOne(DrivingLicense::class, 'people_id');

    $relation->where('type', '=', $this->type);

    return $relation;
  }
}
