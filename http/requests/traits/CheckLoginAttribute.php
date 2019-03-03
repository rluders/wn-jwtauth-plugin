<?php

namespace RLuders\JWTAuth\Http\Requests\Traits;

use RainLab\User\Models\Settings as RainLabUserSettings;

trait CheckLoginAttribute
{
    /**
     * Check if RainLab user is using the username as login field
     *
     * @return boolean
     */
    protected function isUsernameLoginAttribute()
    {
        return RainLabUserSettings::get(
            'login_attribute',
            RainLabUserSettings::LOGIN_EMAIL
        ) == RainLabUserSettings::LOGIN_USERNAME;
    }
}