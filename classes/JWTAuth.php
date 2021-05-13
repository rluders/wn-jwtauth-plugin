<?php

namespace RLuders\JWTAuth\Classes;

use Tymon\JWTAuth\JWTAuth as BaseJWTAuth;

class JWTAuth extends BaseJWTAuth
{
    /**
     * Register user
     *
     * @param array $data
     * @param boolean $activate
     * @return Winter\Storm\Database\Model
     */
    public function register(array $data, $activate = false)
    {
        return $this->auth->register($data, $activate);
    }

    /**
     * Find the user by ID
     *
     * @param int $userId
     * @return Winter\Storm\Database\Model
     */
    public function findUserById($userId)
    {
        return $this->auth->byId($userId);
    }
}