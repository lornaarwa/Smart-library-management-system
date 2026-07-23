<?php

namespace App\Http\Controllers;

use App\Services\CatalogSearchEngine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogSearchController extends Controller
{
    protected CatalogSearchEngine $searchEngine;

    public function __construct(CatalogSearchEngine $searchEngine)
    {
        $this->searchEngine = $searchEngine;
    }

    public function search(Request $request): JsonResponse
    {
        $queryBuilder = $this->searchEngine->search($request->all());
        $results = $queryBuilder->paginate($request->input('per_page', 12));

        return response()->json($results);
    }
}
