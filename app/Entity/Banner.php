<?php

namespace App\Entity;


/**
 * App\Entity\Banner
 *
 * @property integer $id
 * @property integer $shop_id 商店id
 * @property string $image 图片
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereShopId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Banner extends BaseEntity
{
  protected $fillable = ['image', 'shop_id'];

  protected $hidden = [
    'created_at',
    'updated_at',
    'shop_id',
  ];
}
