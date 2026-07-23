<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Http\Middleware\CorsMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddlewareTest extends TestCase
{
    protected CorsMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new CorsMiddleware();
    }

    public function test_it_attaches_cors_headers_to_response(): void
    {
        $request = Request::create('/api/v1/books', 'GET');
        $response = $this->middleware->handle($request, fn () => new Response('OK'));

        $this->assertEquals('*', $response->headers->get('Access-Control-Allow-Origin'));
        $this->assertStringContainsString('GET', $response->headers->get('Access-Control-Allow-Methods'));
    }
}
