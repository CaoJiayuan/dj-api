<?php

namespace App\Http\Middleware;

use Closure;

class RolePassenger
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
    if (!defined('ROLE_TYPE')) {
        define('ROLE_TYPE', ROLE_PASSENGER);
    }
    return $next($request);
  }
}
