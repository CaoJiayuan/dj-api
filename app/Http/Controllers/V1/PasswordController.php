<?php
/**
 * PasswordController.php
 * Date: 16/5/13
 * Time: 下午3:00
 */

namespace App\Http\Controllers\V1;


use App\Entity\Account;
use App\Traits\ResetOrUpdatePassword;
use Illuminate\Http\Request;

class PasswordController extends BaseController
{
  use ResetOrUpdatePassword;

  /**
   *
   * @return array
   */
  public function getResetRole()
  {
    return [
      'username' => 'required|phone',
      'code'     => 'required',
      'password' => 'required',
    ];
  }

  public function getUpdateRole()
  {
    return [
      'old_password' => 'required',
      'password'     => 'required',
    ];
  }

  public function update(Request $request)
  {
    $data = $request->all();
    $account = Account::whereUsername($data['username'])->first();

    if (!$account) {
      return $this->respondUnprocessable('用户不存在');
    }

    $account->update(['password' => bcrypt($data['password'])]);
    
    return $account;
  }
}