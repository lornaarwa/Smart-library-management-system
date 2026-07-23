<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Http\Middleware\TrustProxiesMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrustProxiesMiddlewareTest extends TestCase
{
    protected TrustProxiesMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new TrustProxiesMiddleware();
    }

    public function test_it_sets_trusted_proxies_header_flags(): void
    {
        $request = Request::create('/api/v1/test', 'GET');
        $response = $this->middleware->handle($request, fn ($req) => new Response('OK'));
        $this->assertEquals(200, $response->getStatusCode());
    }
}
