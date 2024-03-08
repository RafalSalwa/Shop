<?php

declare(strict_types=1);

namespace App\Exception;

use App\Exception\Contracts\CartOperationExceptionInterface;
use Exception;

final class CartOperationException extends Exception implements CartOperationExceptionInterface
{
}
