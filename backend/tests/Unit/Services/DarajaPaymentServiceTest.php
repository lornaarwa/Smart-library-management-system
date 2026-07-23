<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use App\Services\DarajaPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DarajaPaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DarajaPaymentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DarajaPaymentService();
    }

    public function test_it_initiates_stk_push_and_returns_payload(): void
    {
        $user = User::create([
            'name' => 'Fine Payer',
            'email' => 'fine@example.com',
            'password' => bcrypt('password123'),
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'member_number' => 'MEM-8001',
            'membership_tier' => 'student',
        ]);

        $book = Book::create([
            'title' => 'Test Book',
            'author' => 'Author',
            'isbn' => '9781234567890',
            'genre' => 'General',
            'total_copies' => 1,
            'available_copies' => 0,
        ]);

        $copy = \App\Models\BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BC-1001',
            'status' => 'checked_out',
            'condition' => 'good',
        ]);

        $loan = Loan::create([
            'member_id' => $member->id,
            'book_copy_id' => $copy->id,
            'loan_date' => now()->subDays(20),
            'due_date' => now()->subDays(5),
            'status' => 'overdue',
        ]);

        $fine = Fine::create([
            'loan_id' => $loan->id,
            'member_id' => $member->id,
            'amount' => 150.00,
            'balance' => 150.00,
            'status' => 'unpaid',
        ]);

        $res = $this->service->initiateStkPush($fine, '0712345678', 150.00);

        $this->assertTrue($res['success']);
        $this->assertEquals('0', $res['ResponseCode']);
        $this->assertStringContainsString('STK Push sent', $res['CustomerMessage']);
    }
}
