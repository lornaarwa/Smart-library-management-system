<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\EnsureHasAccount;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasAccountTest extends TestCase
{
    protected EnsureHasAccount $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new EnsureHasAccount();
    }

    public function test_it_blocks_unauthenticated_requests(): void
    {
        $request = Request::create('/api/v1/auth/me', 'GET');
        $response = $this->middleware->handle($request, fn () => new Response());
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_it_allows_authenticated_users(): void
    {
        $request = Request::create('/api/v1/auth/me', 'GET');
        $user = new User(['name' => 'Member User']);
        $request->setUserResolver(fn () => $user);

        $response = $this->middleware->handle($request, fn () => new Response('OK'));
        $this->assertEquals(200, $response->getStatusCode());
    }
}
