<?php

namespace RLuders\JWTAuth\Tests\Feature;

use RLuders\JWTAuth\Tests\TestCase;

class LogoutTest extends TestCase
{
    public function testInvalidatesTheTokenAndReturns204(): void
    {
        $user  = $this->createUser(['email' => 'logout@example.com']);
        $token = $this->tokenFor($user);

        $this->postJson('/api/auth/logout', [], ['Authorization' => "Bearer {$token}"])
            ->assertStatus(204);
    }

    public function testReturns401WithoutAToken(): void
    {
        $this->postJson('/api/auth/logout')
            ->assertStatus(401);
    }

    public function testTokenCannotBeUsedAfterLogout(): void
    {
        $user  = $this->createUser(['email' => 'logout2@example.com']);
        $token = $this->tokenFor($user);

        $this->postJson('/api/auth/logout', [], ['Authorization' => "Bearer {$token}"])
            ->assertStatus(204);

        // Token should now be blacklisted
        $this->getJson('/api/auth/me', ['Authorization' => "Bearer {$token}"])
            ->assertStatus(401);
    }
}
