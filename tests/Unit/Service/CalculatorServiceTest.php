<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\CalculatorService;
use App\ValueObject\CouponCode;
use App\ValueObject\Summary;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: CalculatorService::class)]
#[UsesClass(className: Summary::class)]
#[UsesClass(className: CouponCode::class)]
class CalculatorServiceTest extends TestCase
{
    public function testCalculateSummaryNoCoupon(): void
    {
        $service = new CalculatorService();
        $netAmount = 1000; // 10.00
        $coupon = null;

        $summary = $service->calculateSummary($netAmount, $coupon);

        $this->assertInstanceOf(Summary::class, $summary);
        $this->assertEquals(1000, $summary->getNet());
        $this->assertEquals(0, $summary->getDiscount());
        $this->assertEquals(230, $summary->getTax());
        $this->assertEquals(2000, $summary->getShipping());
    }

    public function testCalculateSummaryCartDiscount(): void
    {
        $service = new CalculatorService();
        $netAmount = 1000; // 10.00
        $coupon = new CouponCode('cart-discount', '10');

        $summary = $service->calculateSummary($netAmount, $coupon);

        $this->assertInstanceOf(Summary::class, $summary);
        $this->assertEquals(1000, $summary->getNet());
        $this->assertEquals(1, $summary->getDiscount());
        $this->assertEquals(207, $summary->getTax());
        $this->assertEquals(2000, $summary->getShipping());
    }

    public function testCalculateSummaryShippingDiscount(): void
    {
        $service = new CalculatorService();
        $netAmount = 10000; // 100.00
        $coupon = new CouponCode('shipping-discount', '100');

        $summary = $service->calculateSummary($netAmount, $coupon);

        $this->assertInstanceOf(Summary::class, $summary);
        $this->assertEquals(10000, $summary->getNet());
        $this->assertEquals(0, $summary->getDiscount());
        $this->assertEquals(2300, $summary->getTax());
        $this->assertEquals(0, $summary->getShipping());
    }
}
