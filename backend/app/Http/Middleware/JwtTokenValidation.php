<?php

namespace App\Http\Middleware;

use App\Services\AuthSessionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtTokenValidation
{
    protected AuthSessionService $authSessionService;

    public function __construct(AuthSessionService $authSessionService)
    {
        $this->authSessionService = $this->authSessionService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if ($token) {
            $decoded = app(AuthSessionService::class)->validateToken($token);
            if (!$decoded) {
                return response()->json([
                    'error' => 'Invalid Token',
                    'message' => 'JWT Token validation failed or has been revoked.'
                ], 401);
            }
            $request->attributes->set('jwt_payload', $decoded);
        }

        return $next($request);
    }
}
