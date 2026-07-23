<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Member;

class BorrowLimitService
{
    public function getTierLimit(string $membershipTier): int
    {
        return match ($membershipTier) {
            'student' => 5,
            'faculty' => 10,
            'general' => 3,
            default => 3,
        };
    }

    public function canMemberBorrow(Member $member): array
    {
        $activeLoans = Loan::where('member_id', $member->id)
            ->where('status', 'active')
            ->count();

        $allowedLimit = $member->borrow_limit > 0 ? $member->borrow_limit : $this->getTierLimit($member->membership_tier);
        $remaining = max(0, $allowedLimit - $activeLoans);

        return [
            'can_borrow' => $remaining > 0 && !$member->is_banned,
            'active_loans' => $activeLoans,
            'borrow_limit' => $allowedLimit,
            'remaining_slots' => $remaining,
            'is_banned' => $member->is_banned,
        ];
    }

    public function updateMemberLimit(Member $member, int $newLimit): Member
    {
        $member->borrow_limit = $newLimit;
        $member->save();
        return $member;
    }
}
