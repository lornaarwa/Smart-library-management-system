<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LibrarianDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_librarian_can_access_dashboard_metrics(): void
    {
        $librarian = User::create(['name' => 'Lib Admin', 'email' => 'libmetrics@example.com', 'password' => 'secret', 'role' => 'librarian']);

        $response = $this->actingAs($librarian)->getJson('/api/v1/librarian/metrics');
        $response->assertStatus(200)
            ->assertJsonStructure(['total_books', 'total_copies', 'active_loans', 'total_members']);
    }
}
