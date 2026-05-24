<?php

namespace RLuders\JWTAuth\Tests\Unit;

use Mockery;
use RLuders\JWTAuth\Tests\TestCase;
use RLuders\JWTAuth\Classes\AuthAdapter;
use RLuders\JWTAuth\Classes\AuthManager;
use RLuders\JWTAuth\Models\User;

class AuthAdapterTest extends TestCase
{
    private AuthAdapter $adapter;
    private \Mockery\MockInterface $manager;

    public function setUp(): void
    {
        parent::setUp();

        $this->manager = Mockery::mock(AuthManager::class);
        $this->adapter = new AuthAdapter();

        $reflection = new \ReflectionProperty(AuthAdapter::class, 'auth');
        $reflection->setAccessible(true);
        $reflection->setValue($this->adapter, $this->manager);
    }

    public function testReturnsUserWhenByCredentialsSucceeds(): void
    {
        $user = Mockery::mock(User::class);
        $this->manager->shouldReceive('findUserByCredentials')->once()->andReturn($user);
        $this->manager->shouldReceive('setUser')->once()->with($user);

        $this->assertSame($user, $this->adapter->byCredentials(['email' => 'a@b.com', 'password' => 'x']));
    }

    public function testReturnsFalseWhenByCredentialsThrowsAuthException(): void
    {
        $this->manager->shouldReceive('findUserByCredentials')
            ->once()
            ->andThrow(new \Winter\Storm\Auth\AuthException());

        $this->assertFalse($this->adapter->byCredentials(['email' => 'a@b.com', 'password' => 'x']));
    }

    public function testReturnsTheCurrentUserViaUser(): void
    {
        $user = Mockery::mock(User::class);
        $this->manager->shouldReceive('getUser')->once()->andReturn($user);

        $this->assertSame($user, $this->adapter->user());
    }

    public function testReturnsUserWhenByIdFindsOne(): void
    {
        $user = Mockery::mock(User::class);
        $this->manager->shouldReceive('findUserById')->with(1)->andReturn($user);
        $this->manager->shouldReceive('setUser')->once()->with($user);

        $this->assertSame($user, $this->adapter->byId(1));
    }
}
