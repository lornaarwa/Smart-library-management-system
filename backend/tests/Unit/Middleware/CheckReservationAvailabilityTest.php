<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\User;
use App\Http\Middleware\CheckReservationAvailability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckReservationAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected CheckReservationAvailability $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new CheckReservationAvailability();
    }

    public function test_it_blocks_duplicate_reservations(): void
    {
        $user = User::create(['name' => 'Hold User', 'email' => 'hold2@example.com', 'password' => 'secret']);
        $member = Member::create(['user_id' => $user->id, 'member_number' => 'MEM-2222']);
        $book = Book::create(['title' => 'Book', 'author' => 'Author', 'isbn' => '9782222222222', 'genre' => 'Genre']);

        Reservation::create([
            'book_id' => $book->id,
            'member_id' => $member->id,
            'status' => 'pending',
        ]);

        $request = Request::create('/api/v1/reservations', 'POST', ['book_id' => $book->id]);
        $request->setUserResolver(fn () => $user);

        $response = $this->middleware->handle($request, fn () => new Response());
        $this->assertEquals(409, $response->getStatusCode());
    }
}
