<?php

declare(strict_types=1);

namespace App\Exception;

class TooManySubscriptionsException extends \Exception
{
    protected $message = 'Too many subscriptions in the cart.';

    public function __construct(string $message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
