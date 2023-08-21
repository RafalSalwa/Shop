<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class OrderConfirmedEvent extends Event
{
    private int $orderId;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    public function getOrderData(): int
    {
        return $this->orderId;
    }
}