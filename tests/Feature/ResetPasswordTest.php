<?php

namespace RLuders\JWTAuth\Tests\Feature;

use RLuders\JWTAuth\Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    public function testResetsThePasswordWithAValidCode(): void
    {
        $user = $this->createUser(['email' => 'reset@example.com']);
        $code = implode('!', [$user->id, $user->getResetPasswordCode()]);

        $this->postJson('/api/auth/reset-password', [
            'reset_password_code' => $code,
            'password'            => 'NewPassword1!',
        ])
        ->assertStatus(200);
    }

    public function testReturns422ForAnInvalidResetCode(): void
    {
        $this->postJson('/api/auth/reset-password', [
            'reset_password_code' => '999!invalidcode',
            'password'            => 'NewPassword1!',
        ])
        ->assertStatus(422);
    }

    public function testReturns422WhenResetPasswordCodeIsMissing(): void
    {
        $this->postJson('/api/auth/reset-password', ['password' => 'NewPassword1!'])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function testReturns422WhenPasswordIsMissing(): void
    {
        $user = $this->createUser(['email' => 'reset2@example.com']);
        $code = implode('!', [$user->id, $user->getResetPasswordCode()]);

        $this->postJson('/api/auth/reset-password', ['reset_password_code' => $code])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }
}
