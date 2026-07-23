<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\TokenBucketRateLimiter;

class TokenBucketRateLimiterTest extends TestCase
{
    protected TokenBucketRateLimiter $limiter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limiter = new TokenBucketRateLimiter();
    }

    public function test_it_consumes_tokens_and_resets(): void
    {
        $key = 'test_ip_127_0_0_1';
        $this->limiter->reset($key);

        $this->assertTrue($this->limiter->consume($key, 1, 5));
        $this->assertLessThanOrEqual(5, $this->limiter->availableTokens($key, 5));

        $this->limiter->reset($key);
        $this->assertEquals(5, $this->limiter->availableTokens($key, 5));
    }
}
