<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Services\QueueReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected QueueReservationService $reservationService;

    public function __construct(QueueReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user = $request->user();
        $member = Member::where('user_id', $user->id)->firstOrFail();
        $book = Book::findOrFail($validated['book_id']);

        $reservation = $this->reservationService->placeHold($book, $member);

        return response()->json([
            'message' => 'Book reservation queue hold placed successfully',
            'reservation' => $reservation->load('book'),
        ], 201);
    }
}
