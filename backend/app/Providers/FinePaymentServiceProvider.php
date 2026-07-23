<?php

namespace App\Providers;

use App\Services\DarajaPaymentService;
use Illuminate\Support\ServiceProvider;

class FinePaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DarajaPaymentService::class, function () {
            return new DarajaPaymentService();
        });
    }

    public function boot(): void
    {
        //
    }
}
