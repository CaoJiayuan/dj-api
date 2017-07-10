<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Exception\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;

class BeforeRequest
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    return $next($request);
  }
}
