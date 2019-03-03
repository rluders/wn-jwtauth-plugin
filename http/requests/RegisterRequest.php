<?php

namespace RLuders\JWTAuth\Http\Requests;

use RLuders\JWTAuth\Http\Requests\Request;
use RLuders\JWTAuth\Http\Requests\Traits\CheckLoginAttribute;

class RegisterRequest extends Request
{
    use CheckLoginAttribute;

    /**
     * {@inheritDoc}
     */
    public function data()
    {
        $data = $this->all();

        // Password confirmation is optional
        if (!array_key_exists('password_confirmation', $data)) {
            $data['password_confirmation'] = $data['password'];
        }

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email'    => 'required|between:3,64|email|unique:users',
            'password' => 'required|between:4,64|confirmed',
        ];

        if ($this->isUsernameLoginAttribute()) {
            $rules['username'] = 'required|between:3,64|unique:users';
        }

        return $rules;
    }
}
