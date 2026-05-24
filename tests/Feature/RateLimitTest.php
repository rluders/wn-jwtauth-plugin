<?php

namespace RLuders\JWTAuth\Tests\Feature;

use Illuminate\Support\Facades\RateLimiter;
use Winter\User\Models\Settings as WinterUserSettings;
use RLuders\JWTAuth\Tests\TestCase;

class RateLimitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Clear any rate limiter state from previous tests.
        // The 'jwtauth' limiter keys by IP; in tests the IP is 127.0.0.1.
        RateLimiter::clear('127.0.0.1');

        // Override the throttle limit to 3 attempts so the test runs quickly.
        config([
            'jwt.throttle_max_attempts'  => 3,
            'jwt.throttle_decay_minutes' => 1,
        ]);
    }

    public function testLoginReturns429AfterExceedingMaxAttempts(): void
    {
        // Exhaust the allowed attempts with bad credentials.
        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/api/auth/login', [
                'login'    => 'nobody@example.com',
                'password' => 'wrong',
            ])->assertStatus(401);
        }

        // Next attempt should be rate-limited.
        $this->postJson('/api/auth/login', [
            'login'    => 'nobody@example.com',
            'password' => 'wrong',
        ])
        ->assertStatus(429)
        ->assertJson(['error' => ['code' => 'too_many_requests']]);
    }

    public function testRegisterReturns429AfterExceedingMaxAttempts(): void
    {
        WinterUserSettings::set('activate_mode', WinterUserSettings::ACTIVATE_ADMIN);

        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/api/auth/register', [
                'name'                  => "User {$i}",
                'email'                 => "user{$i}@example.com",
                'password'              => 'Password1!',
                'password_confirmation' => 'Password1!',
            ])->assertStatus(201);
        }

        $this->postJson('/api/auth/register', [
            'name'                  => 'Over Limit',
            'email'                 => 'overlimit@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])
        ->assertStatus(429)
        ->assertJson(['error' => ['code' => 'too_many_requests']]);
    }
}
