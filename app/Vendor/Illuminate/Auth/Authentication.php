<?php
/**
 * Authentication.php
 * Date: 16/7/22
 * Time: 下午4:43
 * Created by Caojiayuan
 */

namespace App\Vendor\Illuminate\Auth;


use Dingo\Api\Auth\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authentication extends Auth
{
  public function __construct(Auth $auth)
  {
    parent::__construct($auth->router, $auth->container, $auth->providers);
  }


  protected function throwUnauthorizedException(array $exceptionStack)
  {
    $exception = array_shift($exceptionStack);

    if ($exception === null) {
      $exception = new UnauthorizedHttpException(null, '登录信息验证失败,请重新登录');
    }

    throw $exception;
  }
}