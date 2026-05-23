<?php

namespace RLuders\JWTAuth\Tests\Feature;

use RLuders\JWTAuth\Tests\TestCase;

class RefreshTokenTest extends TestCase
{
    public function testReturnsANewTokenWhenGivenAValidToken(): void
    {
        $user  = $this->createUser(['email' => 'refresh@example.com']);
        $token = $this->tokenFor($user);

        $this->postJson('/api/auth/refresh-token', ['token' => $token])
            ->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function testReturns422WhenTokenIsMissing(): void
    {
        $this->postJson('/api/auth/refresh-token', [])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function testReturns403ForAnInvalidToken(): void
    {
        // Proper 3-segment JWT format with a bad signature triggers
        // TokenSignatureMismatchException extends TokenInvalidException extends JWTException,
        // which RefreshTokenController catches and returns 403.
        $invalidJwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9'
            . '.eyJzdWIiOiIxMjM0NTY3ODkwIn0'
            . '.bad_signature';

        $this->postJson('/api/auth/refresh-token', ['token' => $invalidJwt])
            ->assertStatus(403);
    }
}
