<?php

namespace App\Exception;

use Exception;
use Throwable;

class ProductStockDepletedException extends Exception
{
    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}