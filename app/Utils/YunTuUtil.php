<?php
/**
 * YunTuUtil.php
 * Date: 16/5/16
 * Time: 下午3:14
 */

namespace App\Utils;


use App\Traits\CurlHelper;
use Request;

class YunTuUtil
{
  use CurlHelper;

  public static $tableId = '5795aab2305a2a6e279d1048';

  /**
   * @param $latitude
   * @param $longitude
   * @param int $radius
   * @param int $limit
   * @return array
   */
  public static function around($latitude, $longitude, $radius = QUERY_DISTANCE, $limit = 20)
  {
    $_this = new static;
    $_this->jsonDecode = true;
    $options = self::getOpts();
    $latitude = round($latitude, 5);
    $longitude = round($longitude, 5);

    $data = [
      'key'     => config('yuntu.key'),
      'tableid' => static::$tableId,
      'center'  => "$longitude,$latitude",
      'limit'   => $limit,
      'radius'  => $radius,
    ];

    $result = $_this->curl('http://yuntuapi.amap.com/datasearch/around', false, $data, $options);

    if ($result['respond']) {
      return static::formatResult($result['content']);
    }

    return [];
  }

  protected static function formatResult($result)
  {
    if ($result['status']) {
      return $result['datas'];
    }
    return [];
  }

  /**
   * @return array
   */
  public static function getOpts()
  {
    $ip = Request::ip();
    $options = [
      'header'  => [
        "X-FORWARDED-FOR:$ip",
        "CLIENT-IP:$ip",
        'Accept:application/json;*/*',
      ],
      'referer' => 'http://lbs.amap.com',
    ];

    return $options;
  }
}