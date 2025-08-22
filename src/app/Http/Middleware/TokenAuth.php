<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;

class TokenAuth
{
    public function __construct(private AuthService $authService)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token required'], 401);
        }

        $user = $this->authService->validateToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        // Attach user to request
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
