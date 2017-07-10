<?php
/**
 * RedisStorage.php
 * Date: 16/5/13
 * Time: 上午11:08
 */

namespace App\Utils;


use Cache;
use Toplan\Sms\Storage;

class RedisStorage implements Storage
{

  public function set($key, $value)
  {
    Cache::put($key, $value, 10);
  }

  public function get($key, $default)
  {
    return Cache::get($key, $default);
  }

  public function forget($key)
  {
    Cache::forget($key);
  }
}