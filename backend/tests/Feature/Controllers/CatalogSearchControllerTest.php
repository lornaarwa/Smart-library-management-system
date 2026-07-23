<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CatalogSearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_search_catalog(): void
    {
        Book::create([
            'title' => 'Domain Driven Design',
            'author' => 'Eric Evans',
            'isbn' => '9780321125217',
            'genre' => 'Architecture',
        ]);

        $response = $this->getJson('/api/v1/catalog/search?q=Domain');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.title', 'Domain Driven Design');
    }
}
