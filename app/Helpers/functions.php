<?php

use Illuminate\Http\JsonResponse;

if (! function_exists('apiResponse')) {

    function apiResponse(
        $data,
        string $message = 'Success',
        string $status = 'success',
        int $statusCode = 200
    ): JsonResponse {
        $response = [
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ];

        return response()->json($response, $statusCode);
    }
}