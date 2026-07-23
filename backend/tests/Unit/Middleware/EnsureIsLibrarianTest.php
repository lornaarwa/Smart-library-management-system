<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\EnsureIsLibrarian;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsLibrarianTest extends TestCase
{
    protected EnsureIsLibrarian $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new EnsureIsLibrarian();
    }

    public function test_it_blocks_non_staff_users(): void
    {
        $request = Request::create('/api/v1/librarian/metrics', 'GET');
        $user = new User(['role' => 'member']);
        $request->setUserResolver(fn () => $user);

        $response = $this->middleware->handle($request, fn () => new Response());
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function test_it_allows_librarians(): void
    {
        $request = Request::create('/api/v1/librarian/metrics', 'GET');
        $user = new User(['role' => 'librarian']);
        $request->setUserResolver(fn () => $user);

        $response = $this->middleware->handle($request, fn () => new Response('OK'));
        $this->assertEquals(200, $response->getStatusCode());
    }
}
