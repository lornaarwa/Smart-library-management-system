<?php

namespace App\Services;

use App\Contracts\Services\QueueReservationServiceInterface;
use App\Models\Book;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\User;

class QueueReservationService implements QueueReservationServiceInterface
{
    public function reserveBook(User $user, Book $book): Reservation
    {
        $member = Member::where('user_id', $user->id)->first() ?? Member::where('email', $user->email)->first();
        if (!$member) {
            $member = Member::create([
                'user_id' => $user->id,
                'member_number' => 'MEM-' . rand(1000, 9999),
                'membership_tier' => 'student',
                'is_banned' => false,
            ]);
        }

        return $this->placeHold($book, $member);
    }

    public function cancelReservation(Reservation $reservation): bool
    {
        $reservation->status = 'cancelled';
        return $reservation->save();
    }

    public function getQueuePosition(Reservation $reservation): int
    {
        return (int) $reservation->queue_position;
    }

    public function processNextInQueue(Book $book): ?Reservation
    {
        return $this->fulfillNextReservation($book);
    }

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
