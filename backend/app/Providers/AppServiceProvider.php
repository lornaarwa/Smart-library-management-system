<?php

namespace App\Providers;

use App\Contracts\Services\BorrowLimitServiceInterface;
use App\Contracts\Services\TokenBucketRateLimiterInterface;
use App\Services\BorrowLimitService;
use App\Services\TokenBucketRateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TokenBucketRateLimiterInterface::class, TokenBucketRateLimiter::class);
        $this->app->singleton(TokenBucketRateLimiter::class, TokenBucketRateLimiter::class);

        $this->app->singleton(BorrowLimitServiceInterface::class, BorrowLimitService::class);
        $this->app->singleton(BorrowLimitService::class, BorrowLimitService::class);
    }

    public function boot(): void
    {
        //
    }
}
