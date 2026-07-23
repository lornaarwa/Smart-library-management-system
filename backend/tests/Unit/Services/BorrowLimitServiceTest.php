<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Member;
use App\Models\User;
use App\Services\BorrowLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BorrowLimitServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BorrowLimitService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BorrowLimitService();
    }

    public function test_it_returns_correct_tier_limits(): void
    {
        $this->assertEquals(5, $this->service->getBorrowLimitForTier('student'));
        $this->assertEquals(10, $this->service->getBorrowLimitForTier('faculty'));
        $this->assertEquals(3, $this->service->getBorrowLimitForTier('general'));
    }

    public function test_it_evaluates_member_borrow_status(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'member_number' => 'MEM-1001',
            'membership_tier' => 'student',
            'is_banned' => false,
        ]);

        $status = $this->service->canMemberBorrow($member);
        $this->assertTrue($status['can_borrow']);
        $this->assertEquals(5, $status['borrow_limit']);
        $this->assertEquals(5, $status['remaining_slots']);
    }
}
