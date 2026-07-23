<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasAccount
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'An active authenticated account is required to access dashboards.'
            ], 401);
        }

        return $next($request);
    }
}
