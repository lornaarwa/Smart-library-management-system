<?php

namespace App\Contracts\Services;

use App\Models\User;

interface BorrowLimitServiceInterface
{
    public function canBorrow(User $user): bool;

    public function getActiveLoansCount(User $user): int;

    public function getRemainingQuota(User $user): int;

    public function getBorrowLimitForTier(string $tier): int;
}
