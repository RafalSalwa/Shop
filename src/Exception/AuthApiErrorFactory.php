<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class AuthApiErrorFactory
{
    public static function create(HttpExceptionInterface $exception): AuthenticationExceptionInterface
    {
        return match ($exception->getResponse()->getStatusCode()) {
            Response::HTTP_NOT_FOUND => new UserNotFoundException('User with such credentials does not exists'),
            Response::HTTP_INTERNAL_SERVER_ERROR => new InternalServerErrorException(
                $exception->getResponse()->getContent(),
            ),
            default => new AuthApiRuntimeException(
                $exception->getResponse()->getContent(),
                $exception->getResponse()->getStatusCode(),
                $exception,
            ),
        };
    }
}
