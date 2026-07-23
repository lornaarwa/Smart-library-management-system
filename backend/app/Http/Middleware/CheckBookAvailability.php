<?php

namespace App\Http\Middleware;

use App\Models\Book;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBookAvailability
{
    public function handle(Request $request, Closure $next): Response
    {
        $bookId = $request->input('book_id') ?? $request->route('book');
        if ($bookId) {
            $book = Book::find($bookId);
            if (!$book) {
                return response()->json(['error' => 'Book not found'], 4404);
            }
            if ($book->is_blocked) {
                return response()->json([
                    'error' => 'Book Restricted',
                    'message' => 'This book has been administratively restricted from borrowing.'
                ], 403);
            }
            if ($book->available_copies <= 0 && $request->isMethod('post') && str_contains($request->path(), 'checkout')) {
                return response()->json([
                    'error' => 'No Copies Available',
                    'message' => 'All physical copies of this book are currently on loan.'
                ], 409);
            }
        }

        return $next($request);
    }
}
