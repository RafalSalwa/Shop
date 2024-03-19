<?php

declare(strict_types=1);

namespace App\Messenger\Message;

final class OrderMessage
{
    public function __construct(private readonly int $orderId)
    {
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }
}
