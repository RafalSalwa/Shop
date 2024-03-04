<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

final class TooManySubscriptionsException extends Exception
{
    protected $message = 'Too many subscriptions in the cart.';

    public function __construct(string $message = '', int $code = 0, ?Throwable $throwable = null)
    {
        parent::__construct($message, $code, $throwable);
    }
}
