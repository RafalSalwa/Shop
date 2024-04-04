<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use App\Enum\PaymentProvider;
use App\Enum\SubscriptionTier;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

#[CoversClass(className: OrderItem::class)]
#[UsesClass(className: Product::class)]
#[UsesClass(className: Payment::class)]
#[UsesClass(className: Address::class)]
#[UsesClass(className: Order::class)]
#[UsesClass(className: SubscriptionPlan::class)]
#[UsesClass(className: SubscriptionTier::class)]
final class OrderItemTest extends TestCase
{
    use ProductHelperCartItemTrait;

    private Product $product;

    private Payment $payment;

    private Address $address;

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
        $this->product = $this->getHelperProduct(1);
        $this->payment = new Payment(
            1,
            100_00,
            PaymentProvider::from('stripe'),
            Uuid::v7()->generate(),
        );
        $this->setProtectedProperty($this->payment, 'id', 1);
        $this->address = new Address(1);
    }

    public function testConstructor(): void
    {
        $prodId = 123;
        $quantity = 2;
        $price = 100;
        $name = 'Test Product';
        $itemType = 'normal';
        $order = $this->order;

        $orderItem = new OrderItem($prodId, $quantity, $price, $name, $itemType, $order);

        $this->assertSame($quantity, $orderItem->getQuantity());
        $this->assertSame($price, $orderItem->getPrice());
        $this->assertSame($name, $orderItem->getName());
        $this->assertSame($itemType, $orderItem->getItemType());
    }
}
