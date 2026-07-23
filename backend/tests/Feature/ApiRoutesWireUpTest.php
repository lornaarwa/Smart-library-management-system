<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

class ApiRoutesWireUpTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_v1_api_routes_are_properly_registered(): void
    {
        $routes = collect(Route::getRoutes())->map(fn ($r) => $r->uri());

        $this->assertTrue($routes->contains('api/v1/auth/register'));
        $this->assertTrue($routes->contains('api/v1/auth/login'));
        $this->assertTrue($routes->contains('api/v1/catalog/search'));
        $this->assertTrue($routes->contains('api/v1/books'));
        $this->assertTrue($routes->contains('api/v1/fines/daraja/callback'));
        $this->assertTrue($routes->contains('api/v1/auth/me'));
        $this->assertTrue($routes->contains('api/v1/loans'));
        $this->assertTrue($routes->contains('api/v1/reservations'));
        $this->assertTrue($routes->contains('api/v1/ai/chat'));
        $this->assertTrue($routes->contains('api/v1/librarian/metrics'));
    }

    public function test_health_check_public_catalog_endpoint(): void
    {
        $response = $this->getJson('/api/v1/catalog/search');
        $response->assertStatus(200);
    }
}
