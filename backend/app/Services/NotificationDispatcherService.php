<?php

namespace App\Services;

use App\Contracts\Services\NotificationDispatcherServiceInterface;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationDispatcherService implements NotificationDispatcherServiceInterface
{
    public function sendOverdueNotification(User $user, array $loanDetails): bool
    {
        Log::info("DISPATCH OVERDUE NOTIFICATION to {$user->email}", $loanDetails);
        return true;
    }

    public function sendReservationAvailableNotification(User $user, array $reservationDetails): bool
    {
        Log::info("DISPATCH HOLD READY NOTIFICATION to {$user->email}", $reservationDetails);
        return true;
    }

    public function sendFineReceiptNotification(User $user, array $fineDetails): bool
    {
        Log::info("DISPATCH FINE RECEIPT NOTIFICATION to {$user->email}", $fineDetails);
        return true;
    }

    public function sendOverdueAlert(Loan $loan): void
    {
        $member = $loan->member;
        $bookTitle = $loan->bookCopy->book->title ?? 'Library Book';

        Log::info("DISPATCH OVERDUE NOTIFICATION: Sending overdue email/SMS to member #{$member->member_number} for '{$bookTitle}'");
    }

    public function sendHoldReadyAlert(Member $member, string $bookTitle): void
    {
        Log::info("DISPATCH HOLD READY NOTIFICATION: Book '{$bookTitle}' is ready for pickup by member #{$member->member_number}");
    }

    public function sendFineReceipt(Member $member, float $amount, string $receipt): void
    {
        Log::info("DISPATCH FINE RECEIPT: Sent receipt {$receipt} for KES {$amount} to member #{$member->member_number}");
    }
}
