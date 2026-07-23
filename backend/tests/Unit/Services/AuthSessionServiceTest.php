<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthSessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthSessionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthSessionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AuthSessionService();
    }

    public function test_it_can_create_and_validate_session_token(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser' . rand(100, 999) . '@library.org',
            'password' => bcrypt('password123'),
        ]);

        $token = $this->service->createSessionToken($user);
        $this->assertNotEmpty($token);

        $validatedUser = $this->service->validateSessionToken($token);
        $this->assertNotNull($validatedUser);
        $this->assertEquals($user->id, $validatedUser->id);
    }

    public function test_it_can_invalidate_session_token(): void
    {
        $user = User::create([
            'name' => 'Test User 2',
            'email' => 'testuser2' . rand(100, 999) . '@library.org',
            'password' => bcrypt('password123'),
        ]);
        $token = $this->service->createSessionToken($user);

        $this->assertTrue($this->service->invalidateSessionToken($token));
        $this->assertNull($this->service->validateSessionToken($token));
    }
}
