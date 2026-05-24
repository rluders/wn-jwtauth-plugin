<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Illuminate\Http\Response;
use RLuders\JWTAuth\Classes\ErrorCodes;
use RLuders\JWTAuth\Classes\JWTAuth;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Http\Responses\ErrorResponse;

class GetUserController extends Controller
{
    /**
     * Return the currently authenticated user.
     *
     * @param  \RLuders\JWTAuth\Classes\JWTAuth $auth
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(JWTAuth $auth)
    {
        if (!$user = $auth->user()) {
            return ErrorResponse::json(
                ErrorCodes::USER_NOT_FOUND,
                'The authenticated user could not be found.',
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(compact('user'));
    }
}
