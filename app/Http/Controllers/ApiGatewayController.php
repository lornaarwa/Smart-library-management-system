<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiGatewayController extends Controller
{
    public function proxy(Request $request, string $service = 'default'): JsonResponse
    {
        return response()->json([
            'gateway' => 'SmartLib API Gateway v1.0',
            'target_service' => $service,
            'path' => $request->path(),
            'method' => $request->method(),
            'status' => 'forwarded',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
