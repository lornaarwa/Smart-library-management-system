<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function sendResponse(mixed $data, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function sendError(string $error, array $errorMessages = [], int $code = 400)
    {
        $response = [
            'status' => 'error',
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
