<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

final class SubscriptionPlanNotFoundException extends Exception
{
    public function __construct(string $message = '', int $code = 404, ?Throwable $throwable = null)
    {
        parent::__construct($message, $code, $throwable);
    }
}
