<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $result = JWTToken::verifyToken($token);
        if ($result == "unauthorized") {
            return redirect()->route('login.page')->with('error', 'Unauthorized access. Please login again.');
        } else {
            // Store user info safely
            $request->merge([
                'user_email' => $result->user_email,
                'user_id' => $result->user_id,
            ]);
            return $next($request);
        }
    }
}
