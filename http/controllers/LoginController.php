<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Event;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Classes\ErrorCodes;
use RLuders\JWTAuth\Classes\JWTAuth;
use RLuders\JWTAuth\Http\Requests\LoginRequest;
use RLuders\JWTAuth\Http\Responses\ErrorResponse;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    /**
     * Authenticate a user and return a JWT token.
     *
     * @param  \RLuders\JWTAuth\Classes\JWTAuth            $auth
     * @param  \RLuders\JWTAuth\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(
        JWTAuth $auth,
        LoginRequest $request
    ) {
        $credentials = $request->getCredentials();

        Event::dispatch('Winter.User.beforeAuthenticate', [$this, $credentials]);

        try {
            if (!$token = $auth->attempt($credentials)) {
                return ErrorResponse::json(
                    ErrorCodes::INVALID_CREDENTIALS,
                    'The provided credentials are incorrect.',
                    Response::HTTP_UNAUTHORIZED
                );
            }
        } catch (JWTException $e) {
            return ErrorResponse::json(
                ErrorCodes::COULD_NOT_CREATE_TOKEN,
                'Could not create authentication token.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $auth->setToken($token)->authenticate();

        if ($user->isBanned()) {
            $auth->invalidate();
            return ErrorResponse::json(
                ErrorCodes::USER_IS_BANNED,
                'This account has been banned.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (!$user->is_activated) {
            $auth->invalidate();
            return ErrorResponse::json(
                ErrorCodes::USER_INACTIVE,
                'This account has not been activated.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        Event::dispatch('Winter.User.login', $user);

        $userData = $user->toArray();
        Event::dispatch('rluders.jwtauth.userTransform', [&$userData, $user]);

        return response()->json(['token' => $token, 'user' => $userData]);
    }
}
