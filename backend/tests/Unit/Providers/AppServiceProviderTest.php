<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Contracts\Services\BorrowLimitServiceInterface;
use App\Contracts\Services\TokenBucketRateLimiterInterface;

class AppServiceProviderTest extends TestCase
{
    public function test_it_resolves_bound_services_from_container(): void
    {
        $limiter = $this->app->make(TokenBucketRateLimiterInterface::class);
        $this->assertInstanceOf(TokenBucketRateLimiterInterface::class, $limiter);

        $borrowLimit = $this->app->make(BorrowLimitServiceInterface::class);
        $this->assertInstanceOf(BorrowLimitServiceInterface::class, $borrowLimit);
    }
}
