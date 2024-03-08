<?php

declare(strict_types=1);

namespace App\Exception;

use App\Exception\Contracts\StockOperationExceptionInterface;
use Exception;

final class StockOperationException extends Exception implements StockOperationExceptionInterface
{
}
