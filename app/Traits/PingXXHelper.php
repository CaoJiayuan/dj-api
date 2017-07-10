<?php
/**
 * PingXXHelper.php
 * Date: 16/8/15
 * Time: 下午5:51
 * Created by Caojiayuan
 */

namespace App\Traits;


use Pingpp\Charge;
use Pingpp\Pingpp;

trait PingXXHelper
{
  public function initPingXX()
  {
    Pingpp::setPrivateKeyPath(config_path('rsa_private_key.pem'));
    Pingpp::setApiKey(config('pingxx.api_key'));
  }

  public function createCharge($channel, $orderNo, $amount, array $data = [])
  {
    $this->initPingXX();

    if(env('APP_ENV')=='local') $amount = 1;

    return Charge::create([
      'order_no'    => $orderNo,
      'amount'      => $amount,
      'app'         => ['id' => config('pingxx.app_id')],
      'channel'     => $channel,
      'currency'    => 'cny',
      'client_ip'   => \Request::ip(),
      'subject'     => 'DJ platform order',
      'body'        => json_encode($data),
      'extra'       => [],
      'description' => json_encode($data),
    ]);
  }
}