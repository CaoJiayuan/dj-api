<?php
/**
 * PushMessage.php
 * Date: 16/5/13
 * Time: 下午5:58
 */

namespace App\Traits;


use App\Entity\Account;
use App\Utils\PushUtil;
use Illuminate\Database\Eloquent\Collection;

trait PushMessage
{
  public function transmission($devices, $message, $dataType, $data = [], $device = DEVICE_ANDROID)
  {
    $userData = [
      'type' => $dataType,
    ];

    if ($data) {
      $userData['data'] = $data;
    }

    PushUtil::$device = $device;

    return PushUtil::push($devices, $message, PushUtil::TRANSMISSION, $userData);
  }

  public function notification($devices, $message, $data = [], $device = DEVICE_ANDROID)
  {
    PushUtil::$device = $device;

    return PushUtil::push($devices, $message, PushUtil::NOTIFICATION, $data);
  }

  /**
   * @param Account[]|Account $accounts
   * @param $message
   * @param $dataType
   * @param array $data
   * @return array
   */
  public function transmissionToAccounts($accounts, $message, $dataType, $data = [])
  {
    return $this->pushToAccounts($accounts, $message, $dataType, $data, PushUtil::TRANSMISSION);
  }


  public function notificationToAccounts($accounts, $message, $dataType, $data = [])
  {
    return $this->pushToAccounts($accounts, $message, $dataType, $data, PushUtil::NOTIFICATION);
  }

  /**
   * @param Account|Account[]|array|Collection $accounts
   * @param $message
   * @param $type
   * @param $dataType
   * @param array $data
   * @return array
   */
  public function pushToAccounts($accounts, $message, $dataType, $data = [], $type = PushUtil::TRANSMISSION)
  {
    $devices = [];
    if ($accounts instanceof Account) {
      $devices[$accounts->device] = $accounts->channel_id;
    }

    if ($accounts instanceof Collection || is_array($accounts)) {
      foreach ($accounts as $account) {
        $devices[$account['device']][] = $account['channel_id'];
      }
    }

    $userData = [
      'type' => $dataType,
    ];

    if ($data) {
      $userData['data'] = $data;
    }

    foreach ($devices as $key => $channels) {
      PushUtil::$device = $key;
      PushUtil::push($channels, $message, $type, $userData);
    }

    return [
      'accounts' => $accounts,
      'data'     => $data,
    ];
  }
}