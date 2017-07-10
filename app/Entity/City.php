<?php

namespace App\Entity;


/**
 * App\Entity\City
 *
 * @property integer $id
 * @property integer $province_id 省份id
 * @property string $name 城市名称
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Shop[] $shops
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\City whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\City whereProvinceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\City whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\City whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\City whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\LocalPayRule[] $localPayRule
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Area[] $area
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\JourneyPayRule[] $journeyRule
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\TruckPayRule[] $truckPayRule
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ChauffeurPayRule[] $chauffeurPayRule
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ChauffeurJourneyPayRule[] $chauffeurJourneyPayRule
 */
class City extends BaseEntity
{

  protected $fillable = ['province_id','name'];


  public function shops()
  {
    return $this->hasMany(Shop::class);
  }

  /**
   * @param $name
   * @return \Illuminate\Database\Eloquent\Model|mixed|null|City
   */
  public static function findByName($name)
  {
    return static::where('name', 'like', "%$name%")->first();
  }

  /**
   * @param $key
   * @return City
   */
  public static function findByNameOrId($key)
  {
    if (!is_numeric($key)) {
      return static::findByName($key);
    }

    return static::find($key);
  }

  public function localPayRule()
  {
    $relation = $this->hasMany(LocalPayRule::class);
    $relation->with('car');
    $result = $relation->getResults();
    if (!$result || !$result->first()) {
      $this->id = 0;
      $r = $this->hasMany(LocalPayRule::class);
      $r->with('car');
      return $r;
    }

    return $relation;
  }

  public function journeyRule()
  {
    $relation = $this->hasMany(JourneyPayRule::class);
    $relation->with('car');
    $result = $relation->getResults();
    if (!$result || !$result->first()) {
      $this->id = 0;
      $r = $this->hasMany(JourneyPayRule::class);
      $r->with('car');
      return $r;
    }

    return $relation;
  }

  public function truckPayRule()
  {
    $relation = $this->hasMany(TruckPayRule::class);
    $relation->with('size');
    $result = $relation->getResults();
    if (!$result || !$result->first()) {
      $this->id = 0;
      $r = $this->hasMany(TruckPayRule::class);
      $r->with('size');

      return $r;
    }

    return $relation;
  }

  public function chauffeurPayRule()
  {
    $relation = $this->hasMany(ChauffeurPayRule::class);
    $result = $relation->getResults();
    if (!$result || !$result->first()) {
      $this->id = 0;
      $r = $this->hasMany(ChauffeurPayRule::class);
      return $r;
    }

    return $relation;
  }


  public function chauffeurJourneyPayRule()
  {
    $relation = $this->hasMany(ChauffeurJourneyPayRule::class);
    $result = $relation->getResults();
    if (!$result || !$result->first()) {
      $this->id = 0;
      $r = $this->hasMany(ChauffeurJourneyPayRule::class);
      return $r;
    }

    return $relation;
  }


  
  public function area()
  {
    return $this->hasMany(Area::class);
  }
}
