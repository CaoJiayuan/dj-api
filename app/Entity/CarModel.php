<?php

namespace App\Entity;

/**
 * App\Entity\CarModel
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CarModel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CarModel whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CarModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CarModel whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Entity\LocalPayRule $localPayRule
 * @property boolean $type
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\CarModel whereType($value)
 */
class CarModel extends BaseEntity
{
  protected $fillable = [
    'name',
    'type',
  ];

  protected $visible = [
    'id',
    'type',
    'name',
  ];

  protected $casts = [
    'type' => 'int',
  ];
  public function localPayRule()
  {
    return $this->hasOne(LocalPayRule::class);
  }

  public function cityLocalPayRule($city)
  {
    $city = City::findByNameOrId($city);
  }

  /**
   * @param $type
   * @return CarModel|null
   */
  public static function findByType($type)
  {
    return static::whereType($type)->first();
  }
}
