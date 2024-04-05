<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: OrderItem::class)]
#[UsesClass(className: Order::class)]
#[UsesClass(className: SubscriptionPlan::class)]
#[UsesClass(className: SubscriptionTier::class)]
final class OrderItemTest extends TestCase
{
    use ProductHelperCartItemTrait;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $address = new Address(1);
        $address->setFirstName('John');
        $address->setLastName('Doe');

        $this->order = new Order(
            netAmount: 100_00,
            userId: 1,
            shippingCost: 20_00,
            total: 120_00,
            deliveryAddress: $address,
            bilingAddress: $address,
        );
        $this->setProtectedProperty($this->order, 'id', 1);
    }

    public function testConstructor(): void
    {
        $prodId = 123;
        $quantity = 2;
        $price = 100;
        $name = 'Test Product';
        $itemType = 'normal';

        $orderItem = new OrderItem($prodId, $quantity, $price, $name, $itemType, $this->order);

        $this->assertSame($quantity, $orderItem->getQuantity());
        $this->assertSame($price, $orderItem->getPrice());
        $this->assertSame($name, $orderItem->getName());
        $this->assertSame($itemType, $orderItem->getItemType());
    }
}
