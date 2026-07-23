<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Http\Middleware\IpRateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpRateLimiterTest extends TestCase
{
    protected IpRateLimiter $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new IpRateLimiter();
    }

    public function test_it_blocks_excessive_ip_traffic_in_production(): void
    {
        config(['app.env' => 'production']);
        $ip = '10.0.0.1';
        Cache::put("ip_rate_limit:{$ip}", 301, 60);

        $request = Request::create('/api/v1/catalog/search', 'GET');
        $request->server->set('REMOTE_ADDR', $ip);

        $response = $this->middleware->handle($request, fn () => new Response());
        $this->assertEquals(429, $response->getStatusCode());
    }
}
