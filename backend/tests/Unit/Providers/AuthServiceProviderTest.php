<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Contracts\Services\AuthSessionServiceInterface;

class AuthServiceProviderTest extends TestCase
{
    public function test_it_resolves_auth_session_service_interface(): void
    {
        $service = $this->app->make(AuthSessionServiceInterface::class);
        $this->assertInstanceOf(AuthSessionServiceInterface::class, $service);
    }
}
