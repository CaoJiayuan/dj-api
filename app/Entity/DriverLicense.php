<?php

namespace App\Entity;


/**
 * App\Entity\DriverLicense
 *
 * @property integer $id
 * @property integer $people_id 认证人id
 * @property string $d_image 认证图片
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DriverLicense whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DriverLicense wherePeopleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DriverLicense whereDImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DriverLicense whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DriverLicense whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property boolean $type 认证类型
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DriverLicense whereType($value)
 */
class DriverLicense extends BaseEntity
{

  protected $casts = [
    'people_id' => 'int',
    'type'      => 'int',
  ];
  protected $fillable = [
    'people_id',
    'd_image',
    'type',
    'city_id',
  ];

  public function city()
  {
    return $this->belongsTo(City::class);
  }
}
