<?php

namespace Tests\Unit\Providers;

use Tests\TestCase;
use App\Contracts\Services\CatalogSearchEngineInterface;

class SearchCatalogProviderTest extends TestCase
{
    public function test_it_resolves_catalog_search_engine_interface(): void
    {
        $engine = $this->app->make(CatalogSearchEngineInterface::class);
        $this->assertInstanceOf(CatalogSearchEngineInterface::class, $engine);
    }
}
