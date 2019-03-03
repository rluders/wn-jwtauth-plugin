<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Classes\JWTAuth;
use RLuders\JWTAuth\Http\Requests\ActivationRequest;

class ActivateController extends Controller
{
    /**
     * Activate the user
     *
     * @param JWTAuth           $auth
     * @param ActivationRequest $request
     *
     * @return Illuminate\Http\Response
     */
    public function __invoke(
        JWTAuth $auth,
        ActivationRequest $request
    ) {
        $code = $request->get('activation_code');
        $parts = explode('!', $code);

        // @TODO Can I convert it as validator?
        if (count($parts) != 2) {
            return response()->json(
                ['error' => 'invalid_activation_code'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        list($userId, $code) = $parts;

        // @TODO Can I convert it as validator?
        if (!strlen(trim($userId)) || !strlen(trim($code))) {
            return response()->json(
                ['error' => 'invalid_user'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$user = $auth->findUserById($userId)) {
            return response()->json(
                ['error' => 'user_not_found'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (!$user->attemptActivation($code)) {
            return response()->json(
                ['error' => 'invalid_activation_code'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // @TODO Autologin

        return response()->json([]);
    }
}