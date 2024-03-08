<?php

declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class OrderConfirmedEvent extends Event
{
    public function __construct(private readonly int $orderId)
    {}

    public function getOrderData(): int
    {
        return $this->orderId;
    }
}
