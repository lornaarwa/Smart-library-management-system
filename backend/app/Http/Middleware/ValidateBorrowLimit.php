<?php

namespace App\Http\Middleware;

use App\Models\Loan;
use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateBorrowLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user) {
            $member = Member::where('user_id', $user->id)->first();
            if ($member) {
                $activeLoans = Loan::where('member_id', $member->id)
                    ->where('status', 'active')
                    ->count();

                if ($activeLoans >= $member->borrow_limit) {
                    return response()->json([
                        'error' => 'Borrow Limit Exceeded',
                        'message' => "You have reached your maximum borrowing limit of {$member->borrow_limit} books."
                    ], 422);
                }
            }
        }

        return $next($request);
    }
}
