<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Member;
use App\Models\Reservation;

class QueueReservationService
{
    public function placeHold(Book $book, Member $member): Reservation
    {
        $lastPosition = Reservation::where('book_id', $book->id)
            ->whereIn('status', ['pending', 'ready_for_pickup'])
            ->max('queue_position') ?? 0;

        return Reservation::create([
            'book_id' => $book->id,
            'member_id' => $member->id,
            'queue_position' => $lastPosition + 1,
            'status' => 'pending',
            'reserved_at' => now(),
            'expires_at' => now()->addDays(3),
        ]);
    }

    public function fulfillNextReservation(Book $book): ?Reservation
    {
        $next = Reservation::where('book_id', $book->id)
            ->where('status', 'pending')
            ->orderBy('queue_position', 'asc')
            ->first();

        if ($next) {
            $next->status = 'ready_for_pickup';
            $next->expires_at = now()->addDays(2);
            $next->save();
        }

        return $next;
    }

    public function expireOldHolds(): int
    {
        return Reservation::where('expires_at', '<', now())
            ->where('status', 'ready_for_pickup')
            ->update(['status' => 'expired']);
    }
}
