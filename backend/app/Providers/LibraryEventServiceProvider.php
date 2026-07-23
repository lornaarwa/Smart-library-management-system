<?php

namespace App\Providers;

use App\Contracts\Services\NotificationDispatcherServiceInterface;
use App\Services\NotificationDispatcherService;
use Illuminate\Support\ServiceProvider;

class LibraryEventServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(NotificationDispatcherServiceInterface::class, NotificationDispatcherService::class);
        $this->app->singleton(NotificationDispatcherService::class, NotificationDispatcherService::class);
    }

    public function boot(): void
    {
        // Maps triggers to actions (e.g. BookOverdue -> email notification)
    }
}
