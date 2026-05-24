<?php

namespace RLuders\JWTAuth\Tests\Feature;

use Event;
use RLuders\JWTAuth\Tests\TestCase;
use RLuders\JWTAuth\Models\User;

class LoginTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser(['email' => 'login@example.com', 'password' => 'Password1!']);
    }

    public function testReturnsATokenAndUserOnValidCredentials(): void
    {
        $this->postJson('/api/auth/login', [
            'login'    => 'login@example.com',
            'password' => 'Password1!',
        ])
        ->assertStatus(200)
        ->assertJsonStructure(['token', 'user']);
    }

    public function testReturns401ForInvalidPassword(): void
    {
        $this->postJson('/api/auth/login', [
            'login'    => 'login@example.com',
            'password' => 'wrong',
        ])
        ->assertStatus(401)
        ->assertJson(['error' => ['code' => 'invalid_credentials']]);
    }

    public function testReturns401ForUnknownUser(): void
    {
        $this->postJson('/api/auth/login', [
            'login'    => 'nobody@example.com',
            'password' => 'Password1!',
        ])
        ->assertStatus(401);
    }

    public function testReturns401ForInactiveUser(): void
    {
        $this->createUser(['email' => 'inactive@example.com'], false);

        $this->postJson('/api/auth/login', [
            'login'    => 'inactive@example.com',
            'password' => 'Password1!',
        ])
        ->assertStatus(401)
        ->assertJson(['error' => ['code' => 'user_inactive']]);
    }

    public function testReturns422WhenLoginFieldIsMissing(): void
    {
        $this->postJson('/api/auth/login', ['password' => 'Password1!'])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function testReturns422WhenPasswordFieldIsMissing(): void
    {
        $this->postJson('/api/auth/login', ['login' => 'login@example.com'])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function testUserTransformEventAllowsExtendingUserData(): void
    {
        Event::listen('rluders.jwtauth.userTransform', function (&$userData, $user) {
            $userData['custom_field'] = 'injected';
        });

        $this->postJson('/api/auth/login', [
            'login'    => 'login@example.com',
            'password' => 'Password1!',
        ])
        ->assertStatus(200)
        ->assertJson(['user' => ['custom_field' => 'injected']]);
    }
}
