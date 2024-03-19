<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

final class TooManySubscriptionsException extends Exception
{
    public function __construct(string $message = 'Too many subscriptions in the cart.', ?Throwable $throwable = null)
    {
        parent::__construct($message, 429, $throwable);
    }
}
