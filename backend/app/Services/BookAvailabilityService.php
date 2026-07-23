<?php

namespace App\Services;

use App\Contracts\Services\BookAvailabilityServiceInterface;
use App\Models\Book;
use App\Models\BookCopy;

class BookAvailabilityService implements BookAvailabilityServiceInterface
{
    public function isAvailable(Book $book): bool
    {
        return $this->getAvailableCopyCount($book) > 0 && !$this->isRestricted($book);
    }

    public function getAvailableCopyCount(Book $book): int
    {
        $count = BookCopy::where('book_id', $book->id)
            ->where('status', 'available')
            ->where('condition', 'good')
            ->count();

        return $count > 0 ? $count : (int) $book->available_copies;
    }

    public function isRestricted(Book $book): bool
    {
        return (bool) $book->is_blocked;
    }

    public function evaluateBookStatus(Book $book): array
    {
        $totalCopies = BookCopy::where('book_id', $book->id)->count();
        $availableCopies = $this->getAvailableCopyCount($book);
        $isAvailable = $this->isAvailable($book);

        return [
            'book_id' => $book->id,
            'title' => $book->title,
            'is_blocked' => $book->is_blocked,
            'total_copies' => $totalCopies > 0 ? $totalCopies : $book->total_copies,
            'available_copies' => $availableCopies,
            'is_available_for_checkout' => $isAvailable,
        ];
    }

    public function setOverrideStatus(Book $book, bool $isBlocked): Book
    {
        $book->is_blocked = $isBlocked;
        $book->save();
        return $book;
    }
}
