<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Event;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Classes\ErrorCodes;
use RLuders\JWTAuth\Http\Responses\ErrorResponse;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class LogoutController extends Controller
{
    /**
     * Invalidate the current JWT token (logout).
     *
     * Fires `Winter.User.logout` with the authenticated user before blacklisting
     * the token so listeners can perform cleanup (e.g. clearing sessions).
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        try {
            $jwtAuth = JWTAuth::parseToken();
            $user    = $jwtAuth->authenticate();
            Event::dispatch('Winter.User.logout', [$user]);
            $jwtAuth->invalidate();
        } catch (JWTException $e) {
            return ErrorResponse::json(
                ErrorCodes::COULD_NOT_LOGOUT,
                'The token could not be invalidated.',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->noContent();
    }
}
