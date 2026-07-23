<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Member;
use App\Models\User;
use App\Services\QueueReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QueueReservationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected QueueReservationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new QueueReservationService();
    }

    public function test_it_places_hold_and_increments_queue_position(): void
    {
        $user = User::create([
            'name' => 'Hold User',
            'email' => 'hold@example.com',
            'password' => bcrypt('password123'),
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'member_number' => 'MEM-5001',
            'membership_tier' => 'student',
        ]);

        $book = Book::create([
            'title' => 'Clean Code',
            'author' => 'Robert Martin',
            'isbn' => '9780132350884',
            'genre' => 'Software',
            'total_copies' => 1,
            'available_copies' => 0,
        ]);

        $reservation = $this->service->reserveBook($user, $book);
        $this->assertEquals(1, $reservation->queue_position);
        $this->assertEquals('pending', $reservation->status);
    }
}
