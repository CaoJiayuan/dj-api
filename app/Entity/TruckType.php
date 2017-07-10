<?php

namespace App\Entity;


/**
 * App\Entity\TruckType
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TruckSize[] $sizes
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\TruckType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TruckType extends BaseEntity
{
  protected $fillable = ['name'];

  protected $visible = [
    'id','name','sizes'
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Builder
   */
  public function sizes()
  {
    $relation = $this->hasMany(TruckSize::class);

    return $relation;
  }

  /**
   * @param $name
   * @return null|TruckType
   */
  public static function findByName($name)
  {
    return static::whereLikeFirst('name', $name);
  }
}
