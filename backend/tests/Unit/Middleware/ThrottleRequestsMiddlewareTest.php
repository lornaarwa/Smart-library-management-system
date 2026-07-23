<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Http\Middleware\ThrottleRequestsMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequestsMiddlewareTest extends TestCase
{
    protected ThrottleRequestsMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new ThrottleRequestsMiddleware();
    }

    public function test_it_throttles_requests_when_max_attempts_exceeded(): void
    {
        $request = Request::create('/api/v1/test', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        for ($i = 0; $i < 2; $i++) {
            $response = $this->middleware->handle($request, fn () => new Response('OK'), 2);
        }

        $response = $this->middleware->handle($request, fn () => new Response('OK'), 2);
        $this->assertEquals(429, $response->getStatusCode());
    }
}
