<?php

namespace RLuders\JWTAuth\Tests\Feature;

use Config;
use Winter\Storm\Support\Facades\Mail;
use RLuders\JWTAuth\Tests\TestCase;
use Winter\User\Models\Settings as WinterUserSettings;

class RegisterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function testRegistersAUserAndReturns201(): void
    {
        $this->postJson('/api/auth/register', [
            'name'                  => 'New User',
            'email'                 => 'newuser@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])
        ->assertStatus(201);
    }

    public function testReturns422WhenEmailIsMissing(): void
    {
        $this->postJson('/api/auth/register', ['password' => 'Password1!'])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function testReturns422WhenEmailIsAlreadyTaken(): void
    {
        $this->createUser(['email' => 'taken@example.com']);

        $this->postJson('/api/auth/register', [
            'name'     => 'Another',
            'email'    => 'taken@example.com',
            'password' => 'Password1!',
        ])
        ->assertStatus(422)
        ->assertJsonStructure(['errors']);
    }

    public function testReturns422WhenPasswordIsMissing(): void
    {
        $this->postJson('/api/auth/register', ['email' => 'x@example.com'])
            ->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    public function testActivationUrlConfigOverrideIsRespected(): void
    {
        WinterUserSettings::set('activate_mode', WinterUserSettings::ACTIVATE_USER);
        Config::set('rluders.jwtauth::config.activation_url', 'https://custom.example.com/activate/{code}');

        $this->postJson('/api/auth/register', [
            'name'                  => 'Config User',
            'email'                 => 'configurl@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertStatus(201);

        Mail::assertSent('winter.user::mail.activate', function ($mailable) {
            return str_starts_with($mailable->viewData['link'] ?? '', 'https://custom.example.com/activate/');
        });
    }
}
