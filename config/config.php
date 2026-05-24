<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Activation URL
    |--------------------------------------------------------------------------
    |
    | Override the activation URL used in the email sent to new users when
    | email activation mode is enabled. When set to null, the value from
    | the backend Settings page is used. Set this to override per-environment
    | without touching backend settings.
    |
    | The {code} placeholder is replaced with the activation code.
    |
    | Example: env('ACTIVATION_URL', null)
    |
    */

    'activation_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Reset Password URL
    |--------------------------------------------------------------------------
    |
    | Override the password reset URL used in the forgot-password email.
    | When set to null, the value from the backend Settings page is used.
    |
    | The {code} placeholder is replaced with the reset code.
    |
    | Example: env('RESET_PASSWORD_URL', null)
    |
    */

    'reset_password_url' => null,

];
