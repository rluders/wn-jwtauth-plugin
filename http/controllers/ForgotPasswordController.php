<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Config;
use Illuminate\Http\Response;
use RLuders\JWTAuth\Models\User;
use RLuders\JWTAuth\Models\Settings;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Classes\ErrorCodes;
use RLuders\JWTAuth\Http\Requests\ForgotPasswordRequest;
use RLuders\JWTAuth\Http\Controllers\Traits\CanMakeUrl;
use RLuders\JWTAuth\Http\Responses\ErrorResponse;
use RLuders\JWTAuth\Http\Controllers\Traits\CanSendMail;

class ForgotPasswordController extends Controller
{
    use CanMakeUrl,
        CanSendMail;

    /**
     * Send a password reset email to the given address.
     *
     * Banned and inactive users are treated as not found to avoid account
     * enumeration through differing error responses.
     *
     * @param  \RLuders\JWTAuth\Http\Requests\ForgotPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(
        ForgotPasswordRequest $request
    ) {
        $email = $request->get('email');

        $user = User::findByEmail($email);
        if (!$user || $user->isBanned() || !$user->is_activated) {
            return ErrorResponse::json(
                ErrorCodes::USER_NOT_FOUND,
                'No active account was found for the given email address.',
                Response::HTTP_NOT_FOUND
            );
        }

        $this->sendResetPasswordEmail($user);

        return response()->json([]);
    }

    /**
     * Send the password reset email to a user.
     *
     * @param  \RLuders\JWTAuth\Models\User $user
     * @return void
     */
    protected function sendResetPasswordEmail(User $user): void
    {
        $code = implode('!', [$user->id, $user->getResetPasswordCode()]);
        $link = $this->makeResetPasswordUrl($code);

        $data = [
            'name' => $user->name,
            'link' => $link,
            'code' => $code
        ];

        $this->sendMail(
            $user->email,
            $user->name,
            'winter.user::mail.restore',
            $data
        );
    }

    /**
     * Build the password reset URL containing the reset code.
     *
     * @param  string $code Compound reset code in the format `{userId}!{code}`.
     * @return string
     */
    protected function makeResetPasswordUrl(string $code): string
    {
        $url = Config::get('rluders.jwtauth::config.reset_password_url') ?? Settings::get('reset_password_url');
        return $this->makeUrl($url, $code);
    }
}
