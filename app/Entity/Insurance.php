<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Insurance
 *
 * @property integer $id
 * @property integer $insurance_type_id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insurance whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insurance whereInsuranceTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insurance whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insurance whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Insurance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Insurance extends BaseEntity
{
  protected $visible = [
    'id','name',
  ];
}
