<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Http\Middleware\ApiGatewayProxy;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiGatewayProxyTest extends TestCase
{
    protected ApiGatewayProxy $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new ApiGatewayProxy();
    }

    public function test_it_attaches_gateway_trace_headers(): void
    {
        $request = Request::create('/api/v1/test', 'GET');
        $response = $this->middleware->handle($request, function ($req) {
            $this->assertTrue($req->headers->has('X-Gateway-Trace-Id'));
            return new Response('OK');
        });

        $this->assertEquals('Laravel-Api-Gateway', $response->headers->get('X-Gateway-Processed-By'));
    }
}
