<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class TokenBucketRateLimiter
{
    public function consumeToken(string $key, int $capacity = 60, int $refillRate = 1): bool
    {
        $bucketKey = "token_bucket:{$key}";
        $data = Cache::get($bucketKey, [
            'tokens' => $capacity,
            'last_refill' => time(),
        ]);

        $now = time();
        $elapsed = $now - $data['last_refill'];
        $refill = $elapsed * $refillRate;

        $tokens = min($capacity, $data['tokens'] + $refill);

        if ($tokens < 1) {
            return false;
        }

        Cache::put($bucketKey, [
            'tokens' => $tokens - 1,
            'last_refill' => $now,
        ], 3600);

        return true;
    }
}
