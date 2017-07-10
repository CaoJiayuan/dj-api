<?php

namespace App\Entity;

use Api\StarterKit\Entities\Entity;

/**
 * Class BaseEntity
 *
 * @package App\Entity
 * @property-read integer $created_at
 * @mixin \Eloquent
 */
class BaseEntity extends Entity
{

  protected $hidden = ['updated_at'];


  /**
   * The attributes that should NOT be assignment by ModelHelper::copy method.
   * @var array
   */
  public $cantCopy = [];


  /**
   * @param $column
   * @param $value
   * @return null|static
   */
  public static function whereLikeFirst($column, $value)
  {
    return static::where($column, 'like',"%$value%")->first();
  }
}