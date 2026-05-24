<?php

namespace RLuders\JWTAuth\Tests\Unit;

use RLuders\JWTAuth\Tests\TestCase;
use RLuders\JWTAuth\Models\User;
use Illuminate\Support\Facades\Event;

class UserModelTest extends TestCase
{
    public function testGetJWTIdentifierReturnsTheModelPrimaryKey(): void
    {
        $user = new User();
        $user->id = 42;

        $this->assertSame(42, $user->getJWTIdentifier());
    }

    public function testGetJWTCustomClaimsReturnsEmptyArrayByDefault(): void
    {
        Event::fake();

        $user   = new User();
        $claims = $user->getJWTCustomClaims();

        $this->assertIsArray($claims);
        $this->assertEmpty($claims);
    }

    public function testGetJWTCustomClaimsDispatchesTheCustomClaimsEvent(): void
    {
        Event::fake();

        $user = new User();
        $user->getJWTCustomClaims();

        Event::assertDispatched('rluders.jwtauth.customClaims');
    }

    public function testListenersCanAddCustomClaimsViaTheEvent(): void
    {
        Event::listen('rluders.jwtauth.customClaims', function (&$claims, $user) {
            $claims['role'] = 'admin';
        });

        $user   = new User();
        $claims = $user->getJWTCustomClaims();

        $this->assertArrayHasKey('role', $claims);
        $this->assertSame('admin', $claims['role']);
    }
}
