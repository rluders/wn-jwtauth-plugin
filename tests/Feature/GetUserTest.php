<?php

namespace RLuders\JWTAuth\Tests\Feature;

use RLuders\JWTAuth\Tests\TestCase;

class GetUserTest extends TestCase
{
    public function testReturnsTheAuthenticatedUser(): void
    {
        $user  = $this->createUser(['email' => 'me@example.com']);
        $token = $this->tokenFor($user);

        $this->getJson('/api/auth/me', ['Authorization' => "Bearer {$token}"])
            ->assertStatus(200)
            ->assertJsonStructure(['user']);
    }

    public function testReturns401WithoutAToken(): void
    {
        $this->getJson('/api/auth/me')
            ->assertStatus(401);
    }

    public function testReturns401WithAnInvalidToken(): void
    {
        $this->getJson('/api/auth/me', ['Authorization' => 'Bearer bad.token.here'])
            ->assertStatus(401);
    }
}
