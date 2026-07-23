<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use App\Http\Middleware\ValidateBorrowLimit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateBorrowLimitTest extends TestCase
{
    use RefreshDatabase;

    protected ValidateBorrowLimit $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new ValidateBorrowLimit();
    }

    public function test_it_blocks_borrowing_when_limit_reached(): void
    {
        $user = User::create(['name' => 'Limit User', 'email' => 'limit@example.com', 'password' => 'secret']);
        $member = Member::create([
            'user_id' => $user->id,
            'member_number' => 'MEM-1111',
            'borrow_limit' => 1,
        ]);

        $book = \App\Models\Book::create([
            'title' => 'Test Book',
            'author' => 'Author',
            'isbn' => '9781111111111',
            'genre' => 'General',
        ]);

        $copy = \App\Models\BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BC-1111',
            'status' => 'checked_out',
        ]);

        Loan::create([
            'member_id' => $member->id,
            'book_copy_id' => $copy->id,
            'loan_date' => now(),
            'due_date' => now()->addDays(14),
            'status' => 'active',
        ]);

        $request = Request::create('/api/v1/loans/checkout', 'POST');
        $request->setUserResolver(fn () => $user);

        $response = $this->middleware->handle($request, fn () => new Response());
        $this->assertEquals(422, $response->getStatusCode());
    }
}
