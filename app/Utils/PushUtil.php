<?php
/**
 * PushUtil.php
 * Date: 16/5/20
 * Time: 上午11:38
 */

namespace App\Utils;


use PushSDK;

class PushUtil
{

  /**
   * 透传
   */
  const TRANSMISSION = 0;

  /**
   * 通知
   */
  const NOTIFICATION = 1;

  public static $device = DEVICE_ANDROID;

  public static function init()
  {
    $config = 'baidu' . static::$device;

    $sdk = new PushSDK();
    $apiKey = config($config . '.api_key');
    $sdk->setApiKey($apiKey);
    $sdk->setSecretKey(config($config . '.secret_key'));

    return $sdk;
  }

  /**
   * @param $devices
   * @param $description
   * @param int $type
   * @param array $data
   * @return array
   */
  public static function push($devices, $description, $type = self::NOTIFICATION, $data = [])
  {
    if (!$devices) {
      return 'No device to push';
    }
    $sdk = self::init();

    $msg = self::formatMessage($description, $description, $data);
    $envLocal = env('APP_ENV') == 'local';
    $opts = [
      'msg_type'      => $type,
      'deploy_status' => $envLocal ? 1 : 2,
    ];

    if ($envLocal) {
      \Log::debug('PUSH->>>>>>>', [
        'device'      => static::$device,
        'channels'    => $devices,
        'description' => $description,
        'data'        => $data,
      ]);
    }

    if (is_array($devices)) {

      $pushable = [];

      foreach ($devices as $device) {
        if ($device) $pushable[] = $device;
      }

      $result = $sdk->pushBatchUniMsg($pushable, $msg, $opts);
    } else {
      $result = $sdk->pushMsgToSingleDevice($devices, $msg, $opts);
    }

    if (!$result) {

      $error = [
        'error-code'    => $sdk->getLastErrorCode(),
        'error-message' => $sdk->getLastErrorMsg(),
        'devices'       => $devices,
        'device-type'   => static::$device,
        'env'           => $envLocal ? 'Local' : 'Production',
        'message'       => $msg,
      ];

      if ($envLocal) {
        \Log::debug('PUSH', $error);
      }

      return $error;
    }

    return [
      'channels' => $devices,
      'device'   => static::$device,
      'type'     => $type ? 'Notification' : 'Transmission',
      'env'      => $envLocal ? 'Local' : 'Production',
      'message'  => $msg,
    ];
  }

  public static function pushAll($description, $type = self::NOTIFICATION, $data = [])
  {
    $sdk = self::init();

    $opts = [
      'msg_type' => $type,
    ];

    $msg = self::formatMessage($description, $description, $data);

    $result = $sdk->pushMsgToAll($msg, $opts);

    if (!$result) {
      return ['error code:' . $sdk->getLastErrorCode() => $sdk->getLastErrorMsg()];
    }

    return ['devices' => 'ALL', 'type' => $type ? 'Notification' : 'Transmission', 'message' => $msg];
  }


  protected static function formatMessage($title, $description, $userData = [])
  {
    $android = [
      "title"                    => $title,
      "description"              => $description,
      "notification_builder_id"  => 0,
      "notification_basic_style" => 7,
      "open_type"                => 0,
    ];

    $ios = [
      "aps" => [
        "alert"             => $description,
        "sound"             => "default",
        "badge"             => 1,
        "content-available" => true,
      ],
    ];

    return array_merge($android, $ios, $userData);
  }

  public static function pushTag($tag, $message, $type = self::NOTIFICATION, $data = [], $deleteTag = false)
  {
    $sdk = self::init();

    $msg = self::formatMessage($message, $message, $data);

    $opts = [
      'msg_type' => $type,
    ];

    $result = $sdk->pushMsgToTag($tag, $msg, $opts);

    if (!$result) {
      return ['error code:' . $sdk->getLastErrorCode() => $sdk->getLastErrorMsg()];
    }

    if ($deleteTag) {
      $sdk->deleteTag($tag);
    }

    return ['tag' => $tag, 'type' => $type ? 'Notification' : 'Transmission', 'message' => $msg];
  }
}