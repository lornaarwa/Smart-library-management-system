<?php

namespace App\Providers;

use App\Contracts\Services\CatalogSearchEngineInterface;
use App\Services\CatalogSearchEngine;
use Illuminate\Support\ServiceProvider;

class SearchCatalogProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CatalogSearchEngineInterface::class, CatalogSearchEngine::class);
        $this->app->singleton(CatalogSearchEngine::class, CatalogSearchEngine::class);
    }

    public function boot(): void
    {
        //
    }
}
