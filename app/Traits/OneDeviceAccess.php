<?php
/**
 * Authenticated.php
 * Date: 16/6/3
 * Time: 下午3:33
 */

namespace App\Traits;


use App\Entity\Account;
use App\Utils\PushUtil;

trait OneDeviceAccess
{

  /**
   * @param Account $account
   * @param $channelId
   */
  public function checkOneDevice($account, $channelId)
  {
    if ($account->channel_id && $channelId != $account->channel_id) {
      PushUtil::$device = $account->device;
      PushUtil::push($account->channel_id, '你的账号已在其他手机上登录', PushUtil::TRANSMISSION,
        ['type' => PUSH_DEVICE_OFFLINE, 'id' => null]);
    }
  }
}