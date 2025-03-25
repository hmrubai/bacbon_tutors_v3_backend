<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request has a Bearer token
        if ($token = $request->bearerToken()) {
            try {
                // Attempt to authenticate the user using the JWT token
                $user = JWTAuth::setToken($token)->authenticate();
    
                // If the user is authenticated, set the user on the request
                if ($user) {
                    $request->setUserResolver(fn() => $user);
                }
                } catch (\Exception $e) {
                // If the token is invalid or expired, you can return an error response
                return response()->json(['error' => 'Unauthorized', 'message' => $e->getMessage()], 401);
            }
        }

        return $next($request);
    }
}
