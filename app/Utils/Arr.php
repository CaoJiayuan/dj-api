<?php
/**
 * Arr.php
 * Date: 16/6/7
 * Time: 上午11:44
 */

namespace App\Utils;


use Illuminate\Contracts\Support\Arrayable;

class Arr
{

  public static function sort($array, $key, $desc = false)
  {
    $result = $array;

    usort($result, function ($a, $b) use ($key, $desc) {
      if ($a[$key] > $b[$key]) {
        return $desc ? -1 : 1;
      }
      if ($a[$key] < $b[$key]) {
        return $desc ? 1 : -1;
      }

      return 0;
    });

    unset($array);

    return $result;
  }

  public static function sortDesc($array, $key)
  {
    return static::sort($array, $key, true);
  }

  public static function values($keys, $arr, $_ = null)
  {
    $merge = [];
    $array = func_get_args();
    $k = $array[0];
    unset($array[0]);

    foreach ($array as $item) {

      if ($item instanceof Arrayable) {
        $item = $item->toArray();
      }

      $merge = array_merge($merge, (array)$item);
    }


    $result = [];
    foreach ($k as $name => $key) {

      $needle = $key;
      if (!is_numeric($name)) {
        $needle = $name;
      }

      $r = array_get($merge, $needle, null);
      $arr = explode('.', $key);
      $ke = end($arr);
      $result[$ke] = $r;
    }

    return $result;
  }

  public static function allEquals($array, $column, $value)
  {
    if ($array instanceof Arrayable) {
      $array = $array->toArray();
    }

    $e = 0;
    foreach ($array as $item) {
      if ($item[$column] == $value) {
        $e++;
      }
    }

    if (count($array) == $e) {
      return true;
    }

    return false;
  }

  public static function anyEquals($array, $column, $value)
  {
    if ($array instanceof Arrayable) {
      $array = $array->toArray();
    }

    foreach ($array as $item) {
      if ($item[$column] == $value) {
        return true;
      }
    }

    return false;
  }
}