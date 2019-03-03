<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Illuminate\Http\Response;
use RLuders\JWTAuth\Classes\JWTAuth;
use Illuminate\Routing\Controller;

class GetUserController extends Controller
{
    /**
     * Send the forgot password request
     *
     * @return Illuminate\Http\Response
     */
    public function __invoke(JWTAuth $auth)
    {
        if (!$user = $auth->user()) {
            return response()->json(
                ['error' => 'user_not_found'],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(compact('user'));
    }
}