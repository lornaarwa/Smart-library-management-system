<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TokenBucketRateLimiter;
use App\Services\BorrowLimitService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TokenBucketRateLimiter::class, function () {
            return new TokenBucketRateLimiter();
        });

        $this->app->singleton(BorrowLimitService::class, function () {
            return new BorrowLimitService();
        });
    }

    public function boot(): void
    {
        //
    }
}
