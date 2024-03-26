<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Payment;
use App\Entity\Product;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use App\ValueObject\CouponCode;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Order::class)]
#[UsesClass(className: CouponCode::class)]
#[UsesClass(className: OrderItem::class)]
#[UsesClass(className: Product::class)]
#[UsesClass(className: Payment::class)]
class OrderTest extends TestCase
{
    private Order $order;
    use ProductHelperCartItemTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->order = new Order(100_00, 1, 20_00, 120_00);
        $this->setProtectedProperty($this->order, 'id',1);
        $this->product = $this->getHelperProduct(1);
        $this->payment = new Payment();
        $this->payment->setAmount(100);
        $this->setProtectedProperty($this->payment, 'id',1);
        $this->address = new Address(1);

    }

    public function testGettersAndSetters(): void
    {
        $this->assertEquals(1, $this->order->getId());

        $status = Order::PENDING;
        $this->order->setStatus($status);
        $this->assertEquals($status, $this->order->getStatus());

        $userId = 2;
        $this->order->setUserId($userId);
        $this->assertEquals($userId, $this->order->getUserId());

        $this->assertEquals(100_00, $this->order->getNetAmount());

        $this->assertInstanceOf(ArrayCollection::class, $this->order->getItems());

        $this->assertEquals(20_00, $this->order->getShippingCost());
    }

    public function testCouponMethods(): void
    {
        $coupon = new CouponCode('cart-discount', '10');
        $this->order->applyCoupon($coupon);
        $this->assertEquals($coupon, $this->order->getCoupon());

        $this->order->applyCoupon(null);
        $this->assertNull($this->order->getCoupon());
    }

    public function testAddItem(): void
    {
        $orderItem = new OrderItem($this->product->getId(), 1, 100, $this->product->getName());
        $this->order->addItem($orderItem);
        $this->assertTrue($this->order->getItems()->contains($orderItem));
    }

    public function testPrePersist(): void
    {
        $this->order->prePersist();
        $this->assertInstanceOf(DateTimeImmutable::class, $this->order->getCreatedAt());
    }

    public function testPayments(): void
    {
        $order = $this->order;
        $payment = $this->payment;

        $order->addPayment($payment);
        $this->assertNotNull($order->getLastPayment());
    }

    public function testDeliveryAddress()
    {
        $order = $this->order;
        $address = $this->address;
        $order->setDeliveryAddress($address);
        $this->assertNotNull($order->getDeliveryAddress());
        $order->setBilingAddress($address);
        $this->assertNotNull($order->getBilingAddress());
    }
}
