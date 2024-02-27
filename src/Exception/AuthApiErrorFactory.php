<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

final class AuthApiErrorFactory
{
    public static function create(HttpExceptionInterface $httpException): AuthenticationExceptionInterface
    {
        return match ($httpException->getResponse()->getStatusCode()) {
            Response::HTTP_NOT_FOUND => new UserNotFoundException('User with such credentials does not exists'),
            Response::HTTP_INTERNAL_SERVER_ERROR => new InternalServerErrorException(
                $httpException->getResponse()->getContent(),
            ),
            default => new AuthApiRuntimeException(
                $httpException->getResponse()->getContent(),
                $httpException->getResponse()->getStatusCode(),
                $httpException,
            ),
        };
    }
}
