<?php

namespace RLuders\JWTAuth\Http\Requests\Traits;

use Winter\User\Models\Settings as WinterUserSettings;

trait CheckLoginAttribute
{
    /**
     * Check if Winter user is using the username as login field
     *
     * @return boolean
     */
    protected function isUsernameLoginAttribute()
    {
        return WinterUserSettings::get(
            'login_attribute',
            WinterUserSettings::LOGIN_EMAIL
        ) == WinterUserSettings::LOGIN_USERNAME;
    }
}
