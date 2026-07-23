<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\Book;
use App\Http\Middleware\CheckBookAvailability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBookAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected CheckBookAvailability $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new CheckBookAvailability();
    }

    public function test_it_blocks_restricted_books(): void
    {
        $book = Book::create([
            'title' => 'Blocked Book',
            'author' => 'Author',
            'isbn' => '9789999999999',
            'genre' => 'Secret',
            'is_blocked' => true,
        ]);

        $request = Request::create('/api/v1/reservations', 'POST', ['book_id' => $book->id]);
        $response = $this->middleware->handle($request, fn () => new Response());
        $this->assertEquals(403, $response->getStatusCode());
    }
}
