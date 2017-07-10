<?php

namespace App\Entity;

/**
 * App\Entity\TruckSize
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckSize whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckSize whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckSize whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $truck_type_id 货车类型ID
 * @property integer $width 宽(厘米)
 * @property integer $height 宽(厘米)
 * @property integer $length 长(厘米)
 * @property integer $capacity 载重(千克)
 * @property string $name 名称
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckSize whereTruckTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckSize whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckSize whereHeight($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckSize whereLength($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckSize whereCapacity($value)
 * @property-read \App\Entity\TruckType $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TruckPayRule[] $rule
 */
class TruckSize extends BaseEntity
{
  protected $fillable = [
    'truck_type_id',
    'width',
    'height',
    'length',
  ];

  protected $casts = [
    'width'         => 'int',
    'height'        => 'int',
    'length'        => 'int',
    'truck_type_id' => 'int',
  ];

  protected $visible = [
    'id',
    'width',
    'height',
    'length',
    'type',
    'rule',
    'truck_type_id',
  ];

  public function getNameAttribute()
  {
    $truckType = $this->type;

    if (!$truckType) {
      return null;
    }

    return $truckType->name;
  }

  public function rule()
  {
    return $this->hasMany(TruckPayRule::class, 'truck_size_id');
  }

  public function type()
  {
    return $this->belongsTo(TruckType::class, 'truck_type_id');
  }
}
