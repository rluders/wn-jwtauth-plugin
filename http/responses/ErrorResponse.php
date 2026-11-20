<?php

namespace RLuders\JWTAuth\Http\Responses;

use Illuminate\Http\JsonResponse;

/**
 * Builds the canonical API error response: {"error":{"code":"…","message":"…"}}.
 *
 * Use this everywhere instead of inline response()->json(['error' => ...]) calls
 * so the shape stays consistent as the plugin evolves.
 */
class ErrorResponse
{
    /**
     * Return a JSON response with a structured error payload.
     *
     * @param string $code    Machine-readable error code (use ErrorCodes constants).
     * @param string $message Human-readable description of the error.
     * @param int    $status  HTTP status code.
     * @param array  $headers Extra response headers (for example Retry-After).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function json(string $code, string $message, int $status, array $headers = []): JsonResponse
    {
        return response()->json(
            ['error' => ['code' => $code, 'message' => $message]],
            $status,
            $headers
        );
    }
}
