<?php

namespace App\Http\Middleware;

use App\Models\Reservation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckReservationAvailability
{
    public function handle(Request $request, Closure $next): Response
    {
        $bookId = $request->input('book_id');
        $user = $request->user();

        if ($bookId && $user && $user->member) {
            $existingHold = Reservation::where('book_id', $bookId)
                ->where('member_id', $user->member->id)
                ->whereIn('status', ['pending', 'ready_for_pickup'])
                ->first();

            if ($existingHold) {
                return response()->json([
                    'error' => 'Duplicate Reservation',
                    'message' => 'You already have an active hold or reservation queue spot for this book.'
                ], 409);
            }
        }

        return $next($request);
    }
}
