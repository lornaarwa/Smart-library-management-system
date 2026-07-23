<?php

namespace App\Http\Middleware;

use App\Contracts\Services\AuthSessionServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtTokenValidation
{
    protected AuthSessionServiceInterface $authSessionService;

    public function __construct(AuthSessionServiceInterface $authSessionService)
    {
        $this->authSessionService = $authSessionService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if ($token) {
            $user = $this->authSessionService->validateSessionToken($token);
            if (!$user) {
                return response()->json([
                    'error' => 'Invalid Token',
                    'message' => 'JWT Token validation failed or has been revoked.'
                ], 401);
            }
            $request->setUserResolver(fn () => $user);
        }

        return $next($request);
    }
}
