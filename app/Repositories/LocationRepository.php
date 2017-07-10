<?php
/**
 * LocationRepository.php
 * Date: 16/7/29
 * Time: 下午4:23
 * Created by Caojiayuan
 */

namespace App\Repositories;


use App\Traits\CurlHelper;

class LocationRepository
{
  use CurlHelper;

  private $url = 'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2015/';

  public function getData()
  {
//    $main = file_get_contents($this->url);

    $cache = storage_path('/framework/cache/main.txt');

//    file_put_contents($cache, $main);
//    $main['content'] = mb_convert_encoding($main['content']);
//    $matches = [];
//    $con = file_get_contents($cache);
//    preg_match_all('%<a(.*?)a>%', mb_convert_encoding($con, 'utf-8','gb2312'), $matches);
//
//    dd($matches);
//    return $matches;
  }
}