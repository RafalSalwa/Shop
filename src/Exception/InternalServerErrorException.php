<?php

declare(strict_types=1);

namespace App\Exception;

use App\Exception\Contracts\AuthenticationExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class InternalServerErrorException extends HttpException implements AuthenticationExceptionInterface
{
    public function __construct(string $message, ?Throwable $throwable)
    {
        parent::__construct(statusCode: 500, message: $message, previous: $throwable);
    }
}
