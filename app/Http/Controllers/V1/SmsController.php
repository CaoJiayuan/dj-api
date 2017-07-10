<?php
namespace App\Http\Controllers\V1;

use Api\StarterKit\Controllers\ApiController;
use Illuminate\Http\Request;
use SmsManager;
use Toplan\PhpSms\Sms;

class SmsController extends BaseController
{

  /**
   * 发送短信
   * @param Request $request
   * @return mixed
   */
  public function postCode(Request $request)
  {
    // check phone
    $this->validate($request, [
      'phone' => 'required|phone',
    ]);

    $phone = $request->get('phone');

    //send verify sms
    $code = SmsManager::generateCode();
    $minutes = SmsManager::getCodeValidTime();
    $templates = SmsManager::getVerifySmsTemplates();
    $template = SmsManager::getVerifySmsContent();
    try {
      $content = vsprintf($template, [$code, $minutes]);
    } catch (\Exception $e) {
      $content = $template;
    }

    $result = Sms::make($templates)
      ->to($phone)
      ->data(['code' => $code, 'minutes' => $minutes])
      ->content($content)
      ->send();

    if ($result['success']) {
      $data = SmsManager::getSentInfo();
      $data['sent'] = true;
      $data['mobile'] = $phone;
      $data['code'] = $code;
      $data['deadline_time'] = time() + ($minutes * 60);
      SmsManager::storeSentInfo(null, $data);
      $verifyResult = SmsManager::genResult(true, 'sms_send_success');
      return $this->respondSuccess($verifyResult['message']);
    }
    $verifyResult = SmsManager::genResult(false, 'sms_send_failure');
    return $this->respondUnprocessable($verifyResult['message']);
  }

}