<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Book;
use App\Services\BookAvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookAvailabilityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BookAvailabilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BookAvailabilityService();
    }

    public function test_it_evaluates_book_availability_correctly(): void
    {
        $book = Book::create([
            'title' => 'Refactoring',
            'author' => 'Martin Fowler',
            'isbn' => '9780201485677',
            'genre' => 'Software',
            'total_copies' => 2,
            'available_copies' => 2,
            'is_blocked' => false,
        ]);

        $this->assertTrue($this->service->isAvailable($book));
        $this->assertEquals(2, $this->service->getAvailableCopyCount($book));
        $this->assertFalse($this->service->isRestricted($book));
    }

    public function test_restricted_book_is_not_available(): void
    {
        $book = Book::create([
            'title' => 'Restricted Archives',
            'author' => 'Author',
            'isbn' => '9780000000001',
            'genre' => 'History',
            'total_copies' => 1,
            'available_copies' => 1,
            'is_blocked' => true,
        ]);

        $this->assertFalse($this->service->isAvailable($book));
        $this->assertTrue($this->service->isRestricted($book));
    }
}
