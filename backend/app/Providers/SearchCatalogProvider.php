<?php

namespace App\Providers;

use App\Services\CatalogSearchEngine;
use Illuminate\Support\ServiceProvider;

class SearchCatalogProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CatalogSearchEngine::class, function () {
            return new CatalogSearchEngine();
        });
    }

    public function boot(): void
    {
        //
    }
}
