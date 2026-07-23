<?php

namespace App\Contracts\Services;

use App\Models\Book;

interface BookAvailabilityServiceInterface
{
    public function isAvailable(Book $book): bool;

    public function getAvailableCopyCount(Book $book): int;

    public function isRestricted(Book $book): bool;
}
