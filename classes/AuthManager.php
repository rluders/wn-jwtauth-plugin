<?php

namespace RLuders\JWTAuth\Classes;

use Winter\User\Classes\AuthManager as RainAuthManager;

/**
 * {@inheritDoc}
 */
class AuthManager extends RainAuthManager
{
    /**
     * {@inheritDoc}
     */
    protected $userModel = \RLuders\JWTAuth\Models\User::class;
}
