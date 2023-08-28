<?php

namespace App\Exception;

use Exception;
use Throwable;

class ProductStockDepletedException extends Exception
{
    public function __construct(string $message = 'Product does not have any stock items available', Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}