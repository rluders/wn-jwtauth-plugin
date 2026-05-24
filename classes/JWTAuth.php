<?php

namespace RLuders\JWTAuth\Classes;

use PHPOpenSourceSaver\JWTAuth\JWTAuth as BaseJWTAuth;

/**
 * Extends the base JWTAuth class with WinterCMS-specific helpers.
 *
 * Adds `register()` and `findUserById()` as first-class methods so controllers
 * can interact with user management through the same auth object rather than
 * going through the AuthManager directly.
 */
class JWTAuth extends BaseJWTAuth
{
    /**
     * Register a new user account via the auth adapter.
     *
     * @param  array $data     Registration data (name, email, password, …).
     * @param  bool  $activate Whether to activate the account immediately.
     * @return \RLuders\JWTAuth\Models\User
     */
    public function register(array $data, bool $activate = false): \RLuders\JWTAuth\Models\User
    {
        return $this->auth->register($data, $activate);
    }

    /**
     * Find a user by their primary key via the auth adapter.
     *
     * @param  int|string $userId User primary key.
     * @return \RLuders\JWTAuth\Models\User|null Null when no user exists with that ID.
     */
    public function findUserById($userId): ?\RLuders\JWTAuth\Models\User
    {
        return $this->auth->byId($userId);
    }
}
