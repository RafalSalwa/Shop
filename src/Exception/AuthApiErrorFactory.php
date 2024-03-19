<?php

declare(strict_types=1);

namespace App\Exception;

use App\Client\ApiResponseExtractor;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

final class AuthApiErrorFactory
{
    public static function create(HttpExceptionInterface $httpException): AuthenticationExceptionInterface
    {
        $message = ApiResponseExtractor::getErrorMessage($httpException->getResponse());
        $statusCode = $httpException->getResponse()->getStatusCode();

        return match ($statusCode) {
            Response::HTTP_NOT_FOUND             => new UserNotFoundException('Invalid credentials.', $statusCode),
            Response::HTTP_INTERNAL_SERVER_ERROR => new InternalServerErrorException($message, $statusCode),
            Response::HTTP_BAD_REQUEST           => new BadRequestException($message, $statusCode, $httpException),
            default                              => new AuthApiRuntimeException($message, $statusCode, $httpException),
        };
    }
}
