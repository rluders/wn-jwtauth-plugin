<?php

namespace RLuders\JWTAuth\Tests\Feature;

use RLuders\JWTAuth\Tests\TestCase;

class ActivateTest extends TestCase
{
    public function testActivatesAnAccountWithAValidCode(): void
    {
        $user = $this->createUser(['email' => 'activate@example.com'], false);
        $code = implode('!', [$user->id, $user->getActivationCode()]);

        $this->postJson('/api/auth/account-activation', ['activation_code' => $code])
            ->assertStatus(200);
    }

    public function testReturns422ForAnInvalidActivationCode(): void
    {
        $this->postJson('/api/auth/account-activation', ['activation_code' => '999!invalidcode'])
            ->assertStatus(422);
    }

    public function testReturns422WhenActivationCodeIsMissing(): void
    {
        $this->postJson('/api/auth/account-activation', [])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }
}
