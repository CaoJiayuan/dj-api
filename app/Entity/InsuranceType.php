<?php

namespace App\Entity;

/**
 * App\Entity\InsuranceType
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Insurance[] $insurances
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsuranceType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsuranceType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsuranceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsuranceType whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $insure_id 对应商家ID
 * @property-read \App\Entity\Insure $insure
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\InsuranceType whereInsureId($value)
 */
class InsuranceType extends BaseEntity
{
  protected $fillable = [
    'name','insure_id'
  ];
  protected $visible = [
    'id',
    'name',
    'insurances'
  ];

  public function insurances()
  {
    return $this->hasMany(Insurance::class);
  }

  public function insure()
  {
    return $this->belongsTo(Insure::class);
  }
}
