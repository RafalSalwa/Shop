<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class ItemNotFoundException extends Exception
{
    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}
