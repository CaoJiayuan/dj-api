<?php

namespace App\Entity;

use App\Vendor\Illuminate\RelationNull;
use DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * App\Entity\Shop
 *
 * @property integer $id
 * @property string $shop_name 店名
 * @property integer $people_id 认证人id
 * @property string $s_image 认证图片
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Entity\Account $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Banner[] $banners
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Shop whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Shop whereShopName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Shop wherePeopleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Shop whereSImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Shop distance($lng, $lat)
 * @mixin \Eloquent
 * @property-read \App\Entity\Person $person
 */
class Shop extends BaseEntity
{

  protected $fillable = [
    'shop_name',
    'people_id',
    's_image',
  ];


  public function account()
  {
    if ($this->person) {
      return $this->person->account();
    }

    return new RelationNull($this);
  }

  public function person()
  {
    return $this->belongsTo(Person::class, 'people_id');
  }


  public function scopeDistance(Builder $query, $lng, $lat)
  {
    return $query->select(DB::raw("getDistance($lng, $lat, longitude, latitude)*1000 as dis"));
  }

  public function banners()
  {
    return $this->hasMany(Banner::class);
  }


  public function near($lng, $lat, $page = 1, $limit = 20)
  {
    $shop = self::select([
      'id',
      'name',
      'address',
      'longitude',
      'latitude',
      'account_id',
      DB::raw("getDistance($lng, $lat, longitude, latitude)*1000 as dis"),
    ])->where('status', CERT_REVIEWED);
    $shop->orderBy('dis');
    $this->with('account');
    $shop->forPage($page, $limit);
    $collection = $shop->get();
    foreach ($collection as $item) {
      $orderCount = $item->orderCount();
      $item->orderCount = is_numeric($orderCount) ? $orderCount : 0;
      $avg = $item->score();
      $item->banners;
      $item->score = $avg ? round($avg, 1) : 0;
    }

    return $collection;
  }
}
