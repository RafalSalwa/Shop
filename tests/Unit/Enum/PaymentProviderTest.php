<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\PaymentProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: PaymentProvider::class)]
final class PaymentProviderTest extends TestCase
{
    public function testEnumCases(): void
    {
        // Assert the enum cases
        $this->assertSame('stripe', PaymentProvider::Stripe->value);
        $this->assertSame('paypal', PaymentProvider::Paypal->value);
        $this->assertSame('credit-card', PaymentProvider::CreditCard->value);
        $this->assertSame('payment-on-delivery', PaymentProvider::PaymentOnDelivery->value);
    }

    public function testDefaultValue(): void
    {
        // Test the defaultValue method
        $result = PaymentProvider::defaultValue();
        $this->assertSame('stripe', $result);
    }
}
