<?php

namespace App\Entity;


/**
 * App\Entity\Insure
 *
 * @property integer $id
 * @property string $name 保险商家名
 * @property string $banner 图片
 * @property string $address 地址
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insure whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insure whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insure whereBanner($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insure whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insure whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insure whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\InsuranceType[] $insuranceType
 */
class Insure extends BaseEntity
{
  protected $fillable = [
    'name',
    'banner',
    'address',
  ];

  public function insuranceType()
  {
    return $this->hasMany(InsuranceType::class);
  }
}
