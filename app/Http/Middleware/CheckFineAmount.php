<?php

namespace App\Http\Middleware;

use App\Models\Fine;
use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFineAmount
{
    public function handle(Request $request, Closure $next, float $maxAllowedFine = 500.00): Response
    {
        $user = $request->user();
        if ($user) {
            $member = Member::where('user_id', $user->id)->first();
            if ($member) {
                $totalUnpaid = Fine::where('member_id', $member->id)
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->sum('balance');

                if ($totalUnpaid >= $maxAllowedFine) {
                    return response()->json([
                        'error' => 'Outstanding Fines',
                        'message' => "You have outstanding library fines totaling KES {$totalUnpaid}. Please settle fines before borrowing.",
                        'total_fine' => $totalUnpaid
                    ], 402);
                }
            }
        }

        return $next($request);
    }
}
