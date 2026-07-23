<?php

namespace App\Services;

use App\Contracts\Services\TokenBucketRateLimiterInterface;
use Illuminate\Support\Facades\Cache;

class TokenBucketRateLimiter implements TokenBucketRateLimiterInterface
{
    public function consume(string $key, int $tokens = 1, int $maxTokens = 60, int $decaySeconds = 60): bool
    {
        return $this->consumeToken($key, $maxTokens, 1);
    }

    public function availableTokens(string $key, int $maxTokens = 60): int
    {
        $bucketKey = "token_bucket:{$key}";
        $data = Cache::get($bucketKey, ['tokens' => $maxTokens, 'last_refill' => time()]);
        return (int) $data['tokens'];
    }

    public function reset(string $key): void
    {
        Cache::forget("token_bucket:{$key}");
    }

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
