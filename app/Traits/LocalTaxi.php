<?php
/**
 * LocalTaxi.php
 * Date: 16/8/9
 * Time: 下午1:14
 * Created by Caojiayuan
 */

namespace App\Traits;


use App\Transformers\LocalTripItem;
use App\Utils\YunTuUtil;
use DB;

trait LocalTaxi
{

  /**
   * @param $data
   * @param $pushType
   * @return array
   */
  public function doTaxi($data, $pushType)
  {
    return DB::transaction(function () use ($data, $pushType) {
      $trip = $this->publishTrip($data);

      if ($tableID = arr_get($data, 'table_id')) {
        YunTuUtil::$tableId = $tableID;
      }

      $yunData = YunTuUtil::around(arr_get($data, 'latitude', 0), arr_get($data, 'longitude', 0));
      $ids = [];
      foreach ($yunData as $driver) {
        $ids[] = arr_get($driver, 'accountId', 0);
      }

      $pushData = get_formatted($trip, LocalTripItem::class);


      $this->pushToDriver($ids, $pushType, $pushData);

      return $pushData;
    });
  }

  public function pushToDriver($ids, $pushType, $pushData)
  {

  }
}