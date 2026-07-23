<?php

namespace App\Contracts\Services;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;

interface QueueReservationServiceInterface
{
    public function reserveBook(User $user, Book $book): Reservation;

    public function cancelReservation(Reservation $reservation): bool;

    public function getQueuePosition(Reservation $reservation): int;

    public function processNextInQueue(Book $book): ?Reservation;
}
