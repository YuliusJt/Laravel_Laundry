<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
/* TAMBAHAN*/
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTMiddleware
{
  public function handle(Request $request, Closure $next, ...$roles)
  {
    try {
      $user = JWTAuth::parseToken()->authenticate();
    } catch (Exception $e) {
      if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
        return response()->json([
          'success' => false,
          'message' => 'Token Invalid'
        ]);
      } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
        return response()->json([
          'success' => false,
          'message' => 'Token Expired'
        ]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Authorization token not found'
        ]);
      }
    }
    if ($user && in_array($user->role, $roles)) {
      return $next($request);
    }

    return response()->json([
      'success' => false,
      'message' => 'Authorization token not found'
    ]);
  }
}
