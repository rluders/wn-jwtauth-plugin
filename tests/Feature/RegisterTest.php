<?php

namespace RLuders\JWTAuth\Tests\Feature;

use Winter\Storm\Support\Facades\Mail;
use Winter\User\Models\Settings as WinterUserSettings;
use RLuders\JWTAuth\Tests\TestCase;

class RegisterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function testRegistersAUserAndReturns201WhenActivationModeIsManual(): void
    {
        WinterUserSettings::set('activate_mode', WinterUserSettings::ACTIVATE_ADMIN);

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

    public function testReturnsTokenAndUserWhenActivationModeIsAuto(): void
    {
        WinterUserSettings::set('activate_mode', WinterUserSettings::ACTIVATE_AUTO);

        $this->postJson('/api/auth/register', [
            'name'                  => 'Auto User',
            'email'                 => 'autouser@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])
        ->assertStatus(200)
        ->assertJsonStructure(['token', 'user']);
    }
}
