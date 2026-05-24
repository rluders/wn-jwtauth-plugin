<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Classes\ErrorCodes;
use RLuders\JWTAuth\Classes\JWTAuth;
use RLuders\JWTAuth\Http\Requests\ActivationRequest;
use RLuders\JWTAuth\Http\Responses\ErrorResponse;

class ActivateController extends Controller
{
    /**
     * Activate a user account and return a JWT token for immediate login.
     *
     * @param  \RLuders\JWTAuth\Classes\JWTAuth                $auth
     * @param  \RLuders\JWTAuth\Http\Requests\ActivationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(
        JWTAuth $auth,
        ActivationRequest $request
    ) {
        $code = $request->get('activation_code');
        $parts = explode('!', $code);

        if (count($parts) != 2) {
            return ErrorResponse::json(
                ErrorCodes::INVALID_ACTIVATION_CODE,
                'The activation code format is invalid.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        [$userId, $activationCode] = $parts;

        if (!strlen(trim($userId)) || !strlen(trim($activationCode))) {
            return ErrorResponse::json(
                ErrorCodes::INVALID_USER,
                'The activation code contains invalid user information.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$user = $auth->findUserById($userId)) {
            return ErrorResponse::json(
                ErrorCodes::USER_NOT_FOUND,
                'No user was found for the given activation code.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$user->attemptActivation($activationCode)) {
            return ErrorResponse::json(
                ErrorCodes::INVALID_ACTIVATION_CODE,
                'The activation code is invalid or has already been used.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $token = $auth->fromUser($user);

        return response()->json(compact('token', 'user'));
    }
}
