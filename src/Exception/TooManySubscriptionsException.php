<?php

namespace App\Exception;

use Exception;
use Throwable;

class TooManySubscriptionsException extends Exception
{
    protected $message = 'Too many subscriptions in the cart.';

    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}
