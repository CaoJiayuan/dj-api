<?php
/**
 * ResetPassword.php
 * Date: 16/5/13
 * Time: 下午2:59
 */

namespace App\Traits;


use App\Entity\Account;
use Hash;
use Illuminate\Http\Request;

trait ResetOrUpdatePassword
{
  use ApiAuthenticate;

  public function resetPassword(Request $request)
  {
    $this->validate($request, $this->getResetRole());

    $this->validateCode($request->get('code'));
    
    $account = $this->update($request);

    return $this->respondWithItem($account);
  }


  public function updatePassword(Request $request)
  {
    $this->validate($request, $this->getUpdateRole());

    $oldPassword = $request->get('old_password');
    $password = $request->get('password');

    $this->withToken = false;
    /** @var Account $account */
    $account = $this->getUser();

    if (!Hash::check($oldPassword, $account->password)) {
      return $this->respondForbidden('原密码不正确');
    }

    $account->update(['password' => bcrypt($password)]);

    return $this->respondWithItem($account);
  }
}