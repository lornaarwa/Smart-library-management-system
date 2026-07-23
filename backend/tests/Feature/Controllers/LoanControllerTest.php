<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_librarian_can_checkout_book_to_member(): void
    {
        $librarian = User::create(['name' => 'Librarian', 'email' => 'lib2@example.com', 'password' => 'secret', 'role' => 'librarian']);
        $studentUser = User::create(['name' => 'Student', 'email' => 'student@example.com', 'password' => 'secret', 'role' => 'member']);

        $member = Member::create(['user_id' => $studentUser->id, 'member_number' => 'MEM-7777']);
        $book = Book::create(['title' => 'Clean Architecture', 'author' => 'Robert Martin', 'isbn' => '9780134494166', 'genre' => 'Software', 'total_copies' => 1, 'available_copies' => 1]);
        $copy = BookCopy::create(['book_id' => $book->id, 'barcode' => 'BC-7777', 'status' => 'available']);

        $response = $this->actingAs($librarian)->postJson('/api/v1/librarian/loans/checkout', [
            'barcode' => 'BC-7777',
            'member_id' => $member->id,
            'days' => 14,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Book copy checked out successfully');

        $this->assertDatabaseHas('loans', ['member_id' => $member->id, 'status' => 'active']);
    }
}
