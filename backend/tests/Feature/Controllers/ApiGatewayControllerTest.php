<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiGatewayControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_gateway_proxies_requests(): void
    {
        $user = User::create(['name' => 'Gateway User', 'email' => 'gw@example.com', 'password' => 'secret', 'role' => 'member']);

        $response = $this->actingAs($user)->getJson('/api/v1/gateway/search-service');
        $response->assertStatus(200)
            ->assertJsonPath('target_service', 'search-service')
            ->assertJsonPath('status', 'forwarded');
    }
}
