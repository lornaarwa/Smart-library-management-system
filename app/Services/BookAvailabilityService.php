<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BookCopy;

class BookAvailabilityService
{
    public function evaluateBookStatus(Book $book): array
    {
        $totalCopies = BookCopy::where('book_id', $book->id)->count();
        $availableCopies = BookCopy::where('book_id', $book->id)
            ->where('status', 'available')
            ->where('condition', 'good')
            ->count();

        $isAvailable = $availableCopies > 0 && !$book->is_blocked;

        return [
            'book_id' => $book->id,
            'title' => $book->title,
            'is_blocked' => $book->is_blocked,
            'total_copies' => $totalCopies,
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
