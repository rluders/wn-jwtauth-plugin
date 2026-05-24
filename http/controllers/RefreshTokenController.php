<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Event;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Classes\ErrorCodes;
use RLuders\JWTAuth\Classes\JWTAuth;
use RLuders\JWTAuth\Http\Requests\TokenRequest;
use RLuders\JWTAuth\Http\Responses\ErrorResponse;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class RefreshTokenController extends Controller
{
    /**
     * Refresh a JWT token and return the new token.
     *
     * The old token is invalidated (blacklisted) and a fresh one is issued.
     * Fires `RLuders.JWTAuth.tokenRefreshed` with the authenticated user on success.
     *
     * @param  \RLuders\JWTAuth\Classes\JWTAuth             $auth
     * @param  \RLuders\JWTAuth\Http\Requests\TokenRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(
        JWTAuth $auth,
        TokenRequest $request
    ) {
        $oldToken = $request->get('token');
        $auth->setToken($oldToken);

        try {
            if (!$token = $auth->refresh($oldToken)) {
                return ErrorResponse::json(
                    ErrorCodes::COULD_NOT_REFRESH_TOKEN,
                    'The token could not be refreshed.',
                    Response::HTTP_FORBIDDEN
                );
            }
        } catch (JWTException $e) {
            return ErrorResponse::json(
                ErrorCodes::COULD_NOT_REFRESH_TOKEN,
                $e->getMessage(),
                Response::HTTP_FORBIDDEN
            );
        }

        $user = $auth->setToken($token)->authenticate();
        Event::dispatch('RLuders.JWTAuth.tokenRefreshed', [$user]);

        return response()->json(compact('token'));
    }
}
