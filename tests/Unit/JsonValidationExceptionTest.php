<?php

namespace RLuders\JWTAuth\Tests\Unit;

use Mockery;
use Illuminate\Http\Response;
use RLuders\JWTAuth\Tests\TestCase;
use RLuders\JWTAuth\Exceptions\JsonValidationException;

class JsonValidationExceptionTest extends TestCase
{
    private function makeValidator(): \Mockery\MockInterface
    {
        $bag       = new \Illuminate\Support\MessageBag();
        $validator = Mockery::mock(\Illuminate\Validation\Validator::class);
        $validator->shouldReceive('messages')->andReturn($bag);
        $validator->shouldReceive('errors')->andReturn($bag);
        return $validator;
    }

    public function testHasHttpStatus422(): void
    {
        $exception = new JsonValidationException($this->makeValidator());

        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $exception->getStatusCode());
    }

    public function testReturnsJsonContentTypeHeader(): void
    {
        $exception = new JsonValidationException($this->makeValidator());

        $this->assertArrayHasKey('Content-type', $exception->getHeaders());
        $this->assertStringContainsString('application/json', $exception->getHeaders()['Content-type']);
    }
}
