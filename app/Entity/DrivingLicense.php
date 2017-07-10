<?php

namespace App\Entity;


/**
 * App\Entity\DrivingLicense
 *
 * @property integer $id
 * @property integer $people_id 认证人id
 * @property string $car_id 车牌号码
 * @property string $brand 车辆品牌
 * @property string $car_color 车辆颜色
 * @property string $dr_image 认证图片
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense wherePeopleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereCarId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereBrand($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereCarColor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereDrImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property boolean $car_model 车辆类型
 * @property boolean $type 认证类型
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereCarModel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereType($value)
 * @property integer $truck_size_id 货车尺寸ID
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereTruckSizeId($value)
 * @property boolean $city_id 城市ID
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\DrivingLicense whereCityId($value)
 * @property-read \App\Entity\TruckSize $size
 */
class DrivingLicense extends BaseEntity
{
  protected $casts = [
    'people_id' => 'int',
    'type'      => 'int',
    'car_model' => 'int',
  ];
  protected $fillable = [
    'people_id',
    'car_id',
    'brand',
    'car_color',
    'dr_image',
    'type',
    'city_id',
    'truck_size_id',
    'car_model',
    //ywl
    'policy_photo',
    'policy_photo_2'
  ];

  public function size()
  {
    return $this->belongsTo(TruckSize::class, 'truck_size_id');
  }

  /**
   * @return CarModel|null
   */
  public function carModel()
  {
    return CarModel::findByType($this->car_model);
  }

  public function city()
  {
    return $this->belongsTo(City::class);
  }
}
