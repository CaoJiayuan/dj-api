<?php

namespace App\Http\Middleware;

use App\Entity\Account;
use App\User;
use App\Vendor\Illuminate\Auth\Authentication;
use Dingo\Api\Routing\Router;
use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiAuthenticate
{
  /**
   * Router instance.
   *
   * @var \Dingo\Api\Routing\Router
   */
  protected $router;

  /**
   * Authenticator instance.
   *
   * @var Authentication
   */
  protected $auth;

  /**
   * Create a new auth middleware instance.
   *
   * @param \Dingo\Api\Routing\Router $router
   * @param Authentication $auth
   */
  public function __construct(Router $router, Authentication $auth)
  {
    $this->router = $router;
    $this->auth = $auth;
  }

  /**
   * Perform authentication before a request is executed.
   *
   * @param \Illuminate\Http\Request $request
   * @param \Closure                 $next
   *
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $route = $this->router->getCurrentRoute();

    if (! $this->auth->check(false)) {
      $this->auth->authenticate($route->getAuthenticationProviders());
    }

    $user = \JWTAuth::parseToken()->toUser();
    $token = \JWTAuth::getToken();
    $f = \JWTAuth::manager()->decode($token);
    $loginId = $f->get('login_id');
    if($user->login_id != $loginId) {
      \JWTAuth::invalidate($token);
      throw new UnauthorizedHttpException(null, '你的账号在另一个手机登录');
    }

    return $next($request);
  }
}
