<?php

declare(strict_types=1);

namespace App\Exception;

class ProductStockDepletedException extends \Exception
{
    public function __construct(string $message = '', \Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}
