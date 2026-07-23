<?php

namespace App\Contracts\Services;

use App\Models\User;

interface NotificationDispatcherServiceInterface
{
    public function sendOverdueNotification(User $user, array $loanDetails): bool;

    public function sendReservationAvailableNotification(User $user, array $reservationDetails): bool;

    public function sendFineReceiptNotification(User $user, array $fineDetails): bool;
}
