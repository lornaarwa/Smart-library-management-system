<?php

namespace App\Contracts\Services;

interface TokenBucketRateLimiterInterface
{
    public function consume(string $key, int $tokens = 1, int $maxTokens = 60, int $decaySeconds = 60): bool;

    public function availableTokens(string $key, int $maxTokens = 60): int;

    public function reset(string $key): void;
}
