<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthSessionService;
use App\Http\Middleware\JwtTokenValidation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtTokenValidationTest extends TestCase
{
    use RefreshDatabase;

    protected JwtTokenValidation $middleware;
    protected AuthSessionService $authSessionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authSessionService = new AuthSessionService();
        $this->middleware = new JwtTokenValidation($this->authSessionService);
    }

    public function test_it_validates_bearer_jwt_tokens(): void
    {
        $user = User::create(['name' => 'JWT User', 'email' => 'jwt@example.com', 'password' => 'secret']);
        $token = $this->authSessionService->createSessionToken($user);

        $request = Request::create('/api/v1/auth/me', 'GET');
        $request->headers->set('Authorization', 'Bearer ' . $token);

        $response = $this->middleware->handle($request, function ($req) use ($user) {
            $this->assertEquals($user->id, $req->user()->id);
            return new Response('OK');
        });

        $this->assertEquals(200, $response->getStatusCode());
    }
}
