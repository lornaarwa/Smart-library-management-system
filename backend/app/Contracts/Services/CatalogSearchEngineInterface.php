<?php

namespace App\Contracts\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CatalogSearchEngineInterface
{
    public function search(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function searchByQuery(string $query, int $perPage = 15): LengthAwarePaginator;
}
