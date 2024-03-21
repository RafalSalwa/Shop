<?php

declare(strict_types=1);

namespace App\Exception;

use App\Exception\Contracts\OrderOperationExceptionInterface;
use Exception;

final class OrderOperationException extends Exception implements OrderOperationExceptionInterface
{
}
