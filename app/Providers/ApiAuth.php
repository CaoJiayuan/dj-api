<?php
/**
 * ApiAuth.php
 * Date: 16/7/22
 * Time: 下午1:22
 * Created by Caojiayuan
 */

namespace App\Providers;


use Dingo\Api\Auth\Provider\JWT;
use Dingo\Api\Routing\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiAuth extends JWT
{
  public function authenticate(Request $request, Route $route)
  {
    $token = $this->getToken($request);

    try {
      if (!$user = $this->auth->setToken($token)->authenticate()) {
        throw new UnauthorizedHttpException('JWTAuth', '登录信息验证失败,请重新登录');
      }
    } catch (JWTException $exception) {
      throw new UnauthorizedHttpException('JWTAuth', '登录信息验证失败,请重新登录', $exception);
    }

    return $user;
  }

}