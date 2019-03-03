<?php

namespace RLuders\JWTAuth\Http\Requests;

use RLuders\JWTAuth\Http\Requests\Request;
use RLuders\JWTAuth\Http\Requests\Traits\CheckLoginAttribute;

class LoginRequest extends Request
{
    use CheckLoginAttribute;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => $this->getLoginRules(),
            'password' => 'required|between:4,255'
        ];
    }

    /**
     * Get the login validation rules
     *
     * @return string
     */
    protected function getLoginRules()
    {
        return $this->isUsernameLoginAttribute()
                ? 'required|between:2,255'
                : 'required|email|between:6,255';
    }

    /**
     * Get credentials from request
     *
     * @return array
     */
    public function getCredentials()
    {
        $username = $this->isUsernameLoginAttribute() ? 'username' : 'email';
        return [
            $username => $this->get('login'),
            'password' => $this->get('password')
        ];
    }
}
