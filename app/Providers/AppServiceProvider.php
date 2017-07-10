<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;
use SmsManager;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    if (!defined('LOCAL_APP')) {
      define('LOCAL_APP', env('APP_ENV') == 'local');
    }
    $this->registerValidationRole();
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  public function registerValidationRole()
  {
    \Validator::extend('sms_code', function ($key, $value, $param, Validator $validator) {
      $smsData = SmsManager::retrieveSentInfo(null);

      if (!$smsData || ($smsData['deadline_time'] < time())) {
        SmsManager::forgetSentInfo();
        $validator->setCustomMessages(['验证码已失效']);

        return false;
      }

      if ($smsData['code'] != $value) {
        $validator->setCustomMessages(['验证码不正确']);

        return false;
      }

      return true;
    });
  }
}
