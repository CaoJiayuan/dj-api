<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $fillable = [
      'image',
    ];

  protected $hidden = [
    'id',
    'created_at',
    'updated_at'
  ];
}
