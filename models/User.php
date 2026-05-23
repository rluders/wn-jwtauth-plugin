<?php

namespace RLuders\JWTAuth\Models;

use Event;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Winter\User\Models\User as BaseUser;

class User extends BaseUser implements JWTSubject
{
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * Fires 'rluders.jwtauth.customClaims' — listeners receive (&$claims, $user)
     * and may add entries to the $claims array.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        $claims = [];
        Event::dispatch('rluders.jwtauth.customClaims', [&$claims, $this]);
        return $claims;
    }
}