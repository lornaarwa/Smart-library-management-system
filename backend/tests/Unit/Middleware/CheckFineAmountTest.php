<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use App\Http\Middleware\CheckFineAmount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFineAmountTest extends TestCase
{
    use RefreshDatabase;

    protected CheckFineAmount $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new CheckFineAmount();
    }

    public function test_it_blocks_borrowing_when_unpaid_fines_exceed_limit(): void
    {
        $user = User::create(['name' => 'Fine User', 'email' => 'fineuser@example.com', 'password' => 'secret']);
        $member = Member::create(['user_id' => $user->id, 'member_number' => 'MEM-3333']);

        $book = \App\Models\Book::create(['title' => 'Title', 'author' => 'Author', 'isbn' => '9783333333333', 'genre' => 'Genre']);
        $copy = \App\Models\BookCopy::create(['book_id' => $book->id, 'barcode' => 'BC-3333', 'status' => 'checked_out']);
        $loan = Loan::create(['member_id' => $member->id, 'book_copy_id' => $copy->id, 'loan_date' => now(), 'due_date' => now(), 'status' => 'overdue']);

        Fine::create([
            'loan_id' => $loan->id,
            'member_id' => $member->id,
            'amount' => 600.00,
            'balance' => 600.00,
            'status' => 'unpaid',
        ]);

        $request = Request::create('/api/v1/loans/checkout', 'POST');
        $request->setUserResolver(fn () => $user);

        $response = $this->middleware->handle($request, fn () => new Response());
        $this->assertEquals(402, $response->getStatusCode());
    }
}
