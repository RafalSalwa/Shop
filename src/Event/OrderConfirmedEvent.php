<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class OrderConfirmedEvent extends Event
{
    public function __construct(private readonly int $orderId)
    {
    }

    public function getOrderData(): int
    {
        return $this->orderId;
    }
}