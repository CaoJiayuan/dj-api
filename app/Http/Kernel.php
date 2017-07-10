<?php

namespace App\Http;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Router;

class Kernel extends HttpKernel
{
  /**
   * The application's global HTTP middleware stack.
   *
   * These middleware are run during every request to your application.
   *
   * @var array
   */
  protected $middleware = [
    \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    \App\Http\Middleware\BeforeRequest::class,
  ];

  /**
   * The application's route middleware groups.
   *
   * @var array
   */
  protected $middlewareGroups = [
    'web' => [
      \App\Http\Middleware\EncryptCookies::class,
      \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
      \Illuminate\Session\Middleware\StartSession::class,
      \Illuminate\View\Middleware\ShareErrorsFromSession::class,
//            \App\Http\Middleware\VerifyCsrfToken::class,
    ],

    'api' => [
      'throttle:60,1',
    ],
  ];

  /**
   * The application's route middleware.
   *
   * These middleware may be assigned to groups or used individually.
   *
   * @var array
   */
  protected $routeMiddleware = [
    'auth'             => \App\Http\Middleware\Authenticate::class,
    'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'can'              => \Illuminate\Foundation\Http\Middleware\Authorize::class,
    'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'throttle'         => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'api.passenger'    => \App\Http\Middleware\RolePassenger::class,
    'api.driver'       => \App\Http\Middleware\RoleDriver::class,
    'api.formatter'    => \App\Http\Middleware\ResponseFormatter::class,
    'api.authenticate' => \App\Http\Middleware\ApiAuthenticate::class,
  ];
}
