<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class LogoutController extends Controller
{
    /**
     * Invalidate the current token (logout).
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        try {
            JWTAuth::parseToken()->invalidate();
        } catch (JWTException $e) {
            return response()->json(
                ['error' => 'could_not_invalidate_token'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->noContent();
    }
}
