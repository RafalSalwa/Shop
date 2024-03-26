<?php

declare(strict_types=1);

namespace App\Exception;

use App\Client\ApiResponseExtractor;
use App\Exception\Contracts\AuthenticationExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

final class AuthApiErrorFactory
{
    public static function create(HttpExceptionInterface $httpException): AuthenticationExceptionInterface
    {
        $message = ApiResponseExtractor::getErrorMessage($httpException->getResponse());
        $statusCode = $httpException->getResponse()->getStatusCode();

        return match ($statusCode) {
            Response::HTTP_NOT_FOUND => new UserNotFoundException('Invalid credentials.', $statusCode, $httpException),
            Response::HTTP_INTERNAL_SERVER_ERROR => new InternalServerErrorException($message, $httpException),
            Response::HTTP_BAD_REQUEST => new BadRequestException($message, $statusCode, $httpException),
            Response::HTTP_UNAUTHORIZED => new UnauthenticatedException($message, $statusCode, $httpException),
            default => new AuthApiRuntimeException($message, $statusCode, $httpException),
        };
    }
}
