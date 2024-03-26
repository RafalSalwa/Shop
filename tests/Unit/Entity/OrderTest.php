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
use App\ValueObject\CouponCode;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

#[CoversClass(className: Order::class)]
#[UsesClass(className: CouponCode::class)]
#[UsesClass(className: OrderItem::class)]
#[UsesClass(className: Product::class)]
#[UsesClass(className: Payment::class)]
#[UsesClass(className: Address::class)]
#[UsesClass(className: SubscriptionPlan::class)]
#[UsesClass(className: SubscriptionTier::class)]
class OrderTest extends TestCase
{
    public $product;
    public $payment;
    public $address;
    private Order $order;

    use ProductHelperCartItemTrait;

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
            bilingAddress: $address
        );
        $this->setProtectedProperty($this->order, 'id',1);
        $this->product = $this->getHelperProduct(1);
        $this->payment = new Payment(
            1,
            100_00,
            PaymentProvider::from('stripe'),
            Uuid::v7()->generate(),
        );
        $this->setProtectedProperty($this->payment, 'id',1);
        $this->address = new Address(1);

    }

    public function testGettersAndSetters(): void
    {
        $this->assertSame(1, $this->order->getId());

        $status = Order::PENDING;
        $this->order->setStatus($status);
        $this->assertSame($status, $this->order->getStatus());

        $userId = 2;
        $this->order->setUserId($userId);
        $this->assertSame($userId, $this->order->getUserId());

        $this->assertSame(100_00, $this->order->getNetAmount());

        $this->assertInstanceOf(ArrayCollection::class, $this->order->getItems());

        $this->assertSame(20_00, $this->order->getShippingCost());
    }

    public function testCouponMethods(): void
    {
        $couponCode = new CouponCode('cart-discount', '10');
        $this->order->applyCoupon($couponCode);
        $this->assertEquals($couponCode, $this->order->getCoupon());

        $this->order->applyCoupon(null);
        $this->assertNull($this->order->getCoupon());
    }

    public function testAddItem(): void
    {
        $orderItem = new OrderItem(
            $this->product->getId(),
            1,
            100,
            $this->product->getName(),
            'product',
            $this->order,
        );
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

    public function testDeliveryAddress(): void
    {
        $order = $this->order;
        $address = $this->address;
        $order->setDeliveryAddress($address);
        $this->assertNotNull($order->getDeliveryAddress());
        $order->setBilingAddress($address);
        $this->assertNotNull($order->getBilingAddress());
    }
}
