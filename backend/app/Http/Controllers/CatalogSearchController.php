<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CatalogSearchEngineInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogSearchController extends Controller
{
    protected CatalogSearchEngineInterface $searchEngine;

    public function __construct(CatalogSearchEngineInterface $searchEngine)
    {
        $this->searchEngine = $searchEngine;
    }

    public function search(Request $request): JsonResponse
    {
        $results = $this->searchEngine->search(
            $request->all(),
            (int) $request->input('per_page', 12)
        );

        return response()->json($results);
    }
}
