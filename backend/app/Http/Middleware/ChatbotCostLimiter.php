<?php

namespace App\Http\Middleware;

use App\Models\AiUsageLog;
use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatbotCostLimiter
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user) {
            $member = Member::where('user_id', $user->id)->first();
            if ($member) {
                // Check tokens consumed in last 24 hours
                $recentTokens = AiUsageLog::where('member_id', $member->id)
                    ->where('created_at', '>=', now()->subDay())
                    ->sum('tokens_consumed');

                $dailyTokenLimit = 20000; // Configurable limit per user per day

                if ($recentTokens >= $dailyTokenLimit) {
                    return response()->json([
                        'error' => 'AI Quota Exceeded',
                        'message' => 'Daily AI Assistant token quota reached. Please try again tomorrow.'
                    ], 429);
                }
            }
        }

        return $next($request);
    }
}
