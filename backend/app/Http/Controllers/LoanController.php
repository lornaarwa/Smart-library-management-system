<?php

namespace App\Http\Controllers;

use App\Contracts\Services\BookAvailabilityServiceInterface;
use App\Contracts\Services\BorrowLimitServiceInterface;
use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    protected BorrowLimitServiceInterface $borrowLimitService;
    protected BookAvailabilityServiceInterface $availabilityService;

    public function __construct(
        BorrowLimitServiceInterface $borrowLimitService,
        BookAvailabilityServiceInterface $availabilityService
    ) {
        $this->borrowLimitService = $borrowLimitService;
        $this->availabilityService = $availabilityService;
    }
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Loan::with('bookCopy.book', 'member.user');

        if ($user && $user->role === 'member') {
            $member = Member::where('user_id', $user->id)->first();
            if ($member) {
                $query->where('member_id', $member->id);
            }
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'barcode' => 'required|string|exists:book_copies,barcode',
            'member_id' => 'required|exists:members,id',
            'days' => 'nullable|integer|min:1|max:30',
        ]);

        $copy = BookCopy::where('barcode', $validated['barcode'])->first();
        if ($copy->status !== 'available') {
            return response()->json(['error' => "Book copy barcode {$validated['barcode']} is not available for loan."], 409);
        }

        $days = $validated['days'] ?? 14;
        $loan = Loan::create([
            'book_copy_id' => $copy->id,
            'member_id' => $validated['member_id'],
            'loan_date' => now(),
            'due_date' => now()->addDays($days),
            'status' => 'active',
            'renewal_count' => 0,
        ]);

        $copy->update(['status' => 'checked_out']);
        $copy->book->decrement('available_copies');

        return response()->json(['message' => 'Book copy checked out successfully', 'loan' => $loan->load('bookCopy.book')], 201);
    }

    public function returnBook(Loan $loan): JsonResponse
    {
        if ($loan->status === 'returned') {
            return response()->json(['error' => 'Loan is already returned'], 400);
        }

        $loan->update([
            'returned_date' => now(),
            'status' => 'returned',
        ]);

        $copy = $loan->bookCopy;
        $copy->update(['status' => 'available']);
        $copy->book->increment('available_copies');

        return response()->json(['message' => 'Book returned successfully', 'loan' => $loan]);
    }
}
