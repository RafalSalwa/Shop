<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event;

use App\Entity\Address;
use App\Entity\Order;
use App\Event\OrderConfirmedEvent;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: OrderConfirmedEvent::class)]
#[UsesClass(className: Order::class)]
#[UsesClass(className: Address::class)]
final class OrderConfirmedEventTest extends TestCase
{
    public function testGetOrder(): void
    {
        $address = new Address(1);
        $order = new Order(
            netAmount: 100_00,
            userId: 1,
            shippingCost: 20_00,
            total: 120_00,
            deliveryAddress: $address,
            bilingAddress: $address,
        );

        $orderConfirmedEvent = new OrderConfirmedEvent($order);

        $this->assertSame($order, $orderConfirmedEvent->getOrder());
    }
}
