<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\ProductCartItem;
use App\Factory\OrderItemFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: OrderItemFactory::class)]
#[UsesClass(className: OrderItem::class)]
final class OrderItemFactoryTest extends TestCase
{
    public function testCreateFromCartItem(): void
    {
        $cartItem = $this->createMock(ProductCartItem::class);
        $cartItem->expects($this->exactly(3))
            ->method('getReferencedEntity')
            ->willReturn($this->createMock(Product::class));

        $cartItem->expects($this->once())
            ->method('getQuantity')
            ->willReturn(2);

        $cartItem->expects($this->once())
            ->method('getType')
            ->willReturn('product');

        $order = $this->createMock(Order::class);

        $orderItem = OrderItemFactory::createFromCartItem($cartItem, $order);

        $this->assertInstanceOf(OrderItem::class, $orderItem);
    }
}
