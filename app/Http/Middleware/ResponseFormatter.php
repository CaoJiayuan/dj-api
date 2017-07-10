<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class ResponseFormatter
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
    /** @var Response $response */
    $response = $next($request);
    $success = $response->isSuccessful();

    $code = $response->getStatusCode();
    $body = [
      'code' => $code,
      'msg'  => !$success ? $response->exception->getMessage() : '',
      'data' => $response->original
    ];

    $response->setContent($body);

    return $response;
  }
}
