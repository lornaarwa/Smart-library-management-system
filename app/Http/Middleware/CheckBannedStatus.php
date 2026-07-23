<?php

namespace App\Http\Middleware;

use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user) {
            $member = Member::where('user_id', $user->id)->first();
            if ($member && $member->is_banned) {
                return response()->json([
                    'error' => 'Account Suspended',
                    'message' => 'Your library membership account has been suspended.',
                    'reason' => $member->ban_reason ?? 'Administrative suspension.',
                    'banned_at' => $member->banned_at
                ], 403);
            }
        }

        return $next($request);
    }
}
