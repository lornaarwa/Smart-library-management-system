<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => 'active',
        'message' => 'SmartLib API Backend is running.',
        'documentation' => '/api/v1/catalog/search'
    ]);
});
