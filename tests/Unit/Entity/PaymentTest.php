<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\PaymentProvider;
use App\Tests\Helpers\ProtectedPropertyTrait;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Payment::class)]
#[UsesClass(className: Address::class)]
#[UsesClass(className: Order::class)]
final class PaymentTest extends TestCase
{
    use ProtectedPropertyTrait;

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
        $this->address = new Address(1);
    }

    public function testConstructor(): void
    {
        $userId = 1;
        $amount = 10_00;
        $operationType = PaymentProvider::CreditCard;
        $operationNumber = '1234567890';

        $payment = new Payment($userId, $amount, $operationType, $operationNumber);
        $this->setProtectedProperty($payment, 'id', 1);

        $this->assertSame(1, $payment->getId());
        $this->assertSame($userId, $payment->getUserId());
        $this->assertSame($amount, $payment->getAmount());
        $this->assertSame($operationType, $payment->getOperationType());
        $this->assertSame($operationNumber, $payment->getOperationNumber());
        $this->assertSame(Payment::PENDING, $payment->getStatus());
        $this->assertInstanceOf(DateTimeImmutable::class, $payment->getCreatedAt());
        $this->assertNull($payment->getPaymentDate());
        $this->assertNull($payment->getOrder());
    }

    public function testSettersAndGetters(): void
    {
        $payment = new Payment(1, 10_00, PaymentProvider::CreditCard, '1234567890');

        $amount = 20_00;
        $status = Payment::PROCESSING;
        $dateTimeImmutable = new DateTimeImmutable('now');

        $payment->setAmount($amount);
        $payment->setStatus($status);
        $payment->setPaymentDate($dateTimeImmutable);

        $this->assertSame($amount, $payment->getAmount());
        $this->assertSame($status, $payment->getStatus());
        $this->assertSame($dateTimeImmutable, $payment->getPaymentDate());
        $this->assertSame($dateTimeImmutable->getTimestamp(), $payment->getCreatedAt()->getTimestamp());
    }

    public function testSetOrderAndGetOrder(): void
    {
        $payment = new Payment(1, 10_00, PaymentProvider::Stripe, '1234567890');
        $order = $this->order;

        $payment->setOrder($order);

        $this->assertSame($order, $payment->getOrder());
    }
}
