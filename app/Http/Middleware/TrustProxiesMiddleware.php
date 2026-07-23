<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrustProxiesMiddleware
{
    protected array $trustedProxies = [
        '*' // Trust Cloudflare and local load balancers
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $request->setTrustedProxies(
            $this->trustedProxies,
            Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO
        );

        return $next($request);
    }
}
