<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class InternalServerErrorException extends HttpException implements AuthenticationExceptionInterface
{
    public function __construct(string $message, int $code = 500, ?Throwable $throwable = null)
    {
        parent::__construct($code, $message, $throwable);
    }
}
