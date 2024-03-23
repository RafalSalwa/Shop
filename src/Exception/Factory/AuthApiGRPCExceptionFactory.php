<?php

declare(strict_types=1);

namespace App\Exception\Factory;

use App\Exception\AuthApiRuntimeException;
use App\Exception\AuthenticationExceptionInterface;
use App\Exception\BadRequestException;
use App\Exception\InternalServerErrorException;
use App\Exception\UserAlreadyExistsException;
use App\Exception\UserNotFoundException;
use const Grpc\STATUS_ALREADY_EXISTS;
use const Grpc\STATUS_INTERNAL;
use const Grpc\STATUS_INVALID_ARGUMENT;
use const Grpc\STATUS_NOT_FOUND;

final class AuthApiGRPCExceptionFactory
{
    public static function create(int $statusCode): AuthenticationExceptionInterface
    {
        return match ($statusCode) {
            STATUS_ALREADY_EXISTS => new UserAlreadyExistsException(
                'User with such credentials already exists',
                $statusCode,
            ),
            STATUS_INTERNAL => new InternalServerErrorException(
                'Unknown error has occured, please try again later',
                $statusCode,
            ),
            STATUS_NOT_FOUND => new UserNotFoundException('User not found', $statusCode),
            STATUS_INVALID_ARGUMENT => new BadRequestException('wrong argument provided', $statusCode),
            default => new AuthApiRuntimeException('Cannot determine error', $statusCode),
        };
    }
}
