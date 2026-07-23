<?php

namespace App\Providers;

use App\Services\AuthSessionService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AuthSessionService::class, function () {
            return new AuthSessionService();
        });
    }

    public function boot(): void
    {
        Gate::define('access-admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('access-librarian', function ($user) {
            return in_array($user->role, ['librarian', 'admin']);
        });

        Gate::define('access-member', function ($user) {
            return in_array($user->role, ['member', 'librarian', 'admin']);
        });
    }
}
