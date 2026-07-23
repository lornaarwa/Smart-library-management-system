<?php

namespace App\Providers;

use App\Services\NotificationDispatcherService;
use Illuminate\Support\ServiceProvider;

class LibraryEventServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(NotificationDispatcherService::class, function () {
            return new NotificationDispatcherService();
        });
    }

    public function boot(): void
    {
        // Maps triggers to actions (e.g. BookOverdue -> email notification)
    }
}
