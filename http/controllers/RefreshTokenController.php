<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Classes\JWTAuth;
use RLuders\JWTAuth\Http\Requests\TokenRequest;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class RefreshTokenController extends Controller
{
    /**
     * Refresh the user token
     *
     * @param JWTAuth      $auth
     * @param TokenRequest $request
     *
     * @return Illuminate\Http\Response
     */
    public function __invoke(
        JWTAuth $auth,
        TokenRequest $request
    ) {
        $token = $request->get('token');
        $auth->setToken($token);

        try {
            if (!$token = $auth->refresh($token)) {
                return response()->json(
                    ['error' => 'could_not_refresh_token'],
                    Response::HTTP_FORBIDDEN
                );
            }
        } catch (TokenBlacklistedException $e) {
            return response()->json(
                ['error' => 'given_token_was_blacklisted'],
                Response::HTTP_FORBIDDEN
            );
        }

        $auth->setToken($token);

        return response()->json(compact('token'));
    }
}