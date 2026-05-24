<?php

namespace RLuders\JWTAuth\Classes;

use Winter\Storm\Auth\AuthException;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth as AuthInterface;

/**
 * Bridges the JWT library's auth contract to WinterCMS's AuthManager.
 *
 * The JWT library calls these methods to authenticate users and retrieve them
 * by ID. All results are delegated to the singleton AuthManager so Winter's
 * session/guard state stays in sync.
 */
class AuthAdapter implements AuthInterface
{
    /**
     * @var \RLuders\JWTAuth\Classes\AuthManager
     */
    protected $auth;

    /**
     * Resolve the AuthManager singleton on construction.
     */
    public function __construct()
    {
        $this->auth = AuthManager::instance();
    }

    /**
     * Authenticate a user by credentials and set them as the active user.
     *
     * @param  array $credentials Associative array with login and password keys.
     * @return \RLuders\JWTAuth\Models\User|false User on success, false on failure.
     */
    public function byCredentials(array $credentials = [])
    {
        try {
            $user = $this->auth->findUserByCredentials($credentials);
            $this->auth->setUser($user);

            return $user;
        } catch (AuthException $e) {
            return false;
        }
    }

    /**
     * Authenticate a user by their primary key and set them as the active user.
     *
     * @param  mixed $id User primary key.
     * @return \RLuders\JWTAuth\Models\User|null User on success, null when not found.
     */
    public function byId($id)
    {
        $user = $this->auth->findUserById($id);

        if (!is_null($user)) {
            $this->auth->setUser($user);
        }

        return $user;
    }

    /**
     * Return the currently authenticated user, or null if none.
     *
     * @return \RLuders\JWTAuth\Models\User|null
     */
    public function user()
    {
        return $this->auth->getUser();
    }

    /**
     * Register a new user account.
     *
     * @param  array $data     Registration data (name, email, password, …).
     * @param  bool  $activate Whether to activate the account immediately.
     * @return \RLuders\JWTAuth\Models\User
     */
    public function register($data, $activate = false)
    {
        return $this->auth->register($data, $activate);
    }
}
