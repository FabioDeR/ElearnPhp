<?php
namespace App\API\Exceptions;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data, string $message = "Success", int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error(string $message, int $status = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}