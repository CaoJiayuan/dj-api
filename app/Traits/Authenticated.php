<?php
/**
 * Authed.php
 * Date: 16/5/20
 * Time: 上午10:08
 */

namespace App\Traits;


use App\Entity\Account;
use JWTAuth;

trait Authenticated
{
  /**
   * @return Account
   */
  public function getUser()
  {
    try {
      $authenticate = JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
      return null;
    }

    return $authenticate;
  }

  public function getToken()
  {
    $token = JWTAuth::getToken();

    return $token->get()->get();
  }

}