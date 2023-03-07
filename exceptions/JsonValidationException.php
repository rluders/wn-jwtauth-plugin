<?php

namespace RLuders\JWTAuth\Exceptions;

use Illuminate\Http\Response;
use InvalidArgumentException;
use Winter\Storm\Exception\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class JsonValidationException extends ValidationException implements HttpExceptionInterface
{
    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }

    public function getHeaders(): array
    {
        return [
            'Content-type' => 'application/json;charset=UTF-8'
        ];
    }

    public function toArray(): array
    {
        return ['errors' => $this->getErrors()];
    }
}
