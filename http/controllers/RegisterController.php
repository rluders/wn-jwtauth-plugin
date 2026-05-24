<?php

namespace RLuders\JWTAuth\Http\Controllers;

use Illuminate\Http\Response;
use RLuders\JWTAuth\Models\User;
use Illuminate\Routing\Controller;
use RLuders\JWTAuth\Classes\ErrorCodes;
use RLuders\JWTAuth\Classes\JWTAuth;
use RLuders\JWTAuth\Models\Settings;
use RLuders\JWTAuth\Http\Requests\RegisterRequest;
use RLuders\JWTAuth\Http\Controllers\Traits\CanMakeUrl;
use RLuders\JWTAuth\Http\Responses\ErrorResponse;
use Winter\User\Models\Settings as WinterUserSettings;
use RLuders\JWTAuth\Http\Controllers\Traits\CanSendMail;
use Event;

class RegisterController extends Controller
{
    use CanMakeUrl,
        CanSendMail;

    /**
     * Register a new user account.
     *
     * @param  \RLuders\JWTAuth\Classes\JWTAuth               $auth
     * @param  \RLuders\JWTAuth\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(
        JWTAuth $auth,
        RegisterRequest $request
    ) {
        if (!$this->canRegister()) {
            return ErrorResponse::json(
                ErrorCodes::REGISTRATION_DISABLED,
                'User registration is currently disabled.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        $data = $request->all();

        Event::dispatch('Winter.User.beforeRegister', [&$data]);

        $activationMode = $this->getActivationMode();
        $user = $auth->register($data, ($activationMode == 'auto'));

        Event::dispatch('Winter.User.register', [$user, $data]);

        if ($activationMode == 'email') {
            $this->sendActivationEmail($user);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * Check if the settings allow user registration.
     *
     * @return bool
     */
    protected function canRegister(): bool
    {
        return WinterUserSettings::get('allow_registration', true);
    }

    /**
     * Get the activation mode from configuration as a string.
     *
     * @return string One of 'email', 'auto', or 'manual'.
     */
    protected function getActivationMode(): string
    {
        switch (WinterUserSettings::get('activate_mode')) {
            case WinterUserSettings::ACTIVATE_USER:
                return 'email';
            case WinterUserSettings::ACTIVATE_AUTO:
                return 'auto';
        }

        return 'manual';
    }

    /**
     * Send the activation email to the newly registered user.
     *
     * @param  \RLuders\JWTAuth\Models\User $user
     * @return void
     */
    protected function sendActivationEmail(User $user): void
    {
        $code = implode('!', [$user->id, $user->getActivationCode()]);
        $link = $this->makeActivationUrl($code);

        $data = [
            'name' => $user->name,
            'link' => $link,
            'code' => $code
        ];

        $this->sendMail(
            $user->email,
            $user->name,
            'winter.user::mail.activate',
            $data
        );
    }

    /**
     * Build the account activation URL containing the activation code.
     *
     * @param  string $code Compound activation code in the format `{userId}!{code}`.
     * @return string
     */
    protected function makeActivationUrl(string $code): string
    {
        $url = Settings::get('activation_url');
        return $this->makeUrl($url, $code);
    }
}
