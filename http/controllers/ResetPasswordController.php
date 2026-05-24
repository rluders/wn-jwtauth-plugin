<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Illuminate\Http\Response;
use RLuders\JWTAuth\Models\User;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Classes\ErrorCodes;
use RLuders\JWTAuth\Http\Requests\ResetPasswordRequest;
use RLuders\JWTAuth\Http\Responses\ErrorResponse;

class ResetPasswordController extends Controller
{
    /**
     * Reset a user's password using a password reset code.
     *
     * @param  \RLuders\JWTAuth\Http\Requests\ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(ResetPasswordRequest $request)
    {
        $code = $request->get('reset_password_code');
        $parts = explode('!', $code);

        if (count($parts) != 2) {
            return ErrorResponse::json(
                ErrorCodes::INVALID_RESET_PASSWORD_CODE,
                'The password reset code format is invalid.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        [$userId, $resetCode] = $parts;

        if (!strlen(trim($userId)) || !($user = User::find($userId))) {
            return ErrorResponse::json(
                ErrorCodes::INVALID_USER,
                'The password reset code contains invalid user information.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$user->attemptResetPassword($resetCode, $request->get('password'))) {
            return ErrorResponse::json(
                ErrorCodes::INVALID_RESET_PASSWORD_CODE,
                'The password reset code is invalid or has expired.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return response()->json([]);
    }
}
