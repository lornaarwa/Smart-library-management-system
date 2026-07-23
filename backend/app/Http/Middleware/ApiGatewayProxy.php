<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiGatewayProxy
{
    public function handle(Request $request, Closure $next): Response
    {
        // Inject Gateway routing headers and trace IDs
        $traceId = 'gw-' . bin2hex(random_bytes(8));
        $request->headers->set('X-Gateway-Trace-Id', $traceId);
        $request->headers->set('X-Gateway-Timestamp', (string) microtime(true));

        $response = $next($request);

        $response->headers->set('X-Gateway-Processed-By', 'Laravel-Api-Gateway');
        return $response;
    }
}
