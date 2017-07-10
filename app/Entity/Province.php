<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Province
 *
 * @property integer $id
 * @property string $name 省份名称
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Province whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Province whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Province whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Province whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\City[] $cities
 */
class Province extends Model
{
    //
  protected $fillable = ['name'];

  public function cities()
  {
    return $this->hasMany(City::class);
  }
}
