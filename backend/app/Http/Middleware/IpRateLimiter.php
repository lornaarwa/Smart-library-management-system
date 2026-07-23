<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class IpRateLimiter
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.env') === 'production') {
            $ip = $request->ip();
            $cacheKey = "ip_rate_limit:{$ip}";
            $requests = Cache::get($cacheKey, 0);

            if ($requests > 300) { // 300 requests per minute
                return response()->json([
                    'error' => 'IP Rate Exceeded',
                    'message' => 'Suspicious traffic detected from your IP address.'
                ], 429);
            }

            Cache::put($cacheKey, $requests + 1, 60);
        }

        return $next($request);
    }
}
