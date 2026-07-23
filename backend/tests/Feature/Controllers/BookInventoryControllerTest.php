<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookInventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_librarian_can_create_book_and_copies(): void
    {
        $librarian = User::create([
            'name' => 'Librarian User',
            'email' => 'lib@library.org',
            'password' => bcrypt('password123'),
            'role' => 'librarian',
        ]);

        $response = $this->actingAs($librarian)->postJson('/api/v1/librarian/books', [
            'isbn' => '9780132350884',
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'genre' => 'Software Engineering',
            'initial_copies' => 2,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('book.title', 'Clean Code');

        $this->assertDatabaseHas('books', ['title' => 'Clean Code']);
        $this->assertDatabaseCount('book_copies', 2);
    }
}
