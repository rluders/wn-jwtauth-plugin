<?php

namespace RLuders\JWTAuth\Tests\Feature;

use Winter\Storm\Support\Facades\Mail;
use RLuders\JWTAuth\Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function testSendsResetEmailForAnExistingActivatedUser(): void
    {
        $this->createUser(['email' => 'forgot@example.com']);

        $this->postJson('/api/auth/forgot-password', ['email' => 'forgot@example.com'])
            ->assertStatus(200);
    }

    public function testReturns404ForUnknownEmail(): void
    {
        $this->postJson('/api/auth/forgot-password', ['email' => 'nobody@example.com'])
            ->assertStatus(404);
    }

    public function testReturns422WhenEmailIsMissing(): void
    {
        $this->postJson('/api/auth/forgot-password', [])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function testReturns404ForInactiveUser(): void
    {
        $this->createUser(['email' => 'inactive@example.com'], false);

        $this->postJson('/api/auth/forgot-password', ['email' => 'inactive@example.com'])
            ->assertStatus(404);
    }
}
