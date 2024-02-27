<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class UserNotFoundException extends Exception implements AuthenticationExceptionInterface
{
    public function __construct(string $message = '', int $code = 404, ?Throwable $throwable = null)
    {
        parent::__construct($message, $code, $throwable);
    }
}
