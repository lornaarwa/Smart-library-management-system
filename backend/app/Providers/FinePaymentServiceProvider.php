<?php

namespace App\Providers;

use App\Contracts\Services\DarajaPaymentServiceInterface;
use App\Services\DarajaPaymentService;
use Illuminate\Support\ServiceProvider;

class FinePaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DarajaPaymentServiceInterface::class, DarajaPaymentService::class);
        $this->app->singleton(DarajaPaymentService::class, DarajaPaymentService::class);
    }

    public function boot(): void
    {
        //
    }
}
