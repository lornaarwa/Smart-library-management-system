<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Reservation;
use App\Services\BorrowLimitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LibrarianDashboardController extends Controller
{
    protected BorrowLimitService $borrowLimitService;

    public function __construct(BorrowLimitService $borrowLimitService)
    {
        $this->borrowLimitService = $borrowLimitService;
    }

    public function metrics(): JsonResponse
    {
        return response()->json([
            'total_books' => Book::count(),
            'total_copies' => BookCopy::count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'overdue_loans' => Loan::where('status', 'overdue')->count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'total_unpaid_fines' => Fine::where('status', 'unpaid')->sum('balance'),
            'total_members' => Member::count(),
        ]);
    }

    public function configureBorrowLimit(Request $request, Member $member): JsonResponse
    {
        $validated = $request->validate([
            'borrow_limit' => 'required|integer|min:1|max:20',
        ]);

        $updated = $this->borrowLimitService->updateMemberLimit($member, $validated['borrow_limit']);

        return response()->json([
            'message' => 'Member borrow limit updated successfully',
            'member' => $updated,
        ]);
    }

    public function toggleBookRestriction(Book $book): JsonResponse
    {
        $book->is_blocked = !$book->is_blocked;
        $book->save();

        return response()->json([
            'message' => $book->is_blocked ? 'Book restricted from borrowing' : 'Book restriction lifted',
            'book' => $book,
        ]);
    }
}
