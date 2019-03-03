<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Illuminate\Http\Response;
use RLuders\JWTAuth\Models\User;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Http\Requests\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    /**
     * Reset the user password
     *
     * @param ResetPasswordRequest $request
     *
     * @return Illuminate\Http\Response
     */
    public function __invoke(ResetPasswordRequest $request)
    {
        $code = $request->get('reset_password_code');
        $parts = explode('!', $code);

        // @TODO Can I convert it as validator?
        if (count($parts) != 2) {
            return response()->json(
                ['error' => 'invalid_reset_password_code'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        list($userId, $code) = $parts;

        // @TODO Can I convert it as validator?
        if (!strlen(trim($userId)) || !($user = User::find($userId))) {
            return response()->json(
                ['error' => 'invalid_user'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$user->attemptResetPassword($code, $request->get('password'))) {
            return response()->json(
                ['error' => 'invalid_reset_password_code'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return response()->json([]);
    }
}