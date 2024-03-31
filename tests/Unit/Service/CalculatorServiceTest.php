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
final class CalculatorServiceTest extends TestCase
{
    public function testCalculateSummaryNoCoupon(): void
    {
        $calculatorService = new CalculatorService();
        $netAmount = 1000; // 10.00
        $coupon = null;

        $summary = $calculatorService->calculateSummary($netAmount, $coupon);

        $this->assertSame('0.00', $summary->getDiscount());
        $this->assertSame(230, $summary->getTax());
        $this->assertSame(2000, $summary->getShipping());
    }

    public function testCalculateSummaryCartDiscount(): void
    {
        $calculatorService = new CalculatorService();
        $netAmount = 1000; // 10.00
        $couponCode = new CouponCode('cart-discount', 10);

        $summary = $calculatorService->calculateSummary($netAmount, $couponCode);

        $this->assertSame('1.00', $summary->getDiscount());
        $this->assertSame(207, $summary->getTax());
        $this->assertSame(2000, $summary->getShipping());
    }

    public function testCalculateSummaryShippingDiscount(): void
    {
        $calculatorService = new CalculatorService();
        $netAmount = 10000; // 100.00
        $couponCode = new CouponCode('shipping-discount', 100);

        $summary = $calculatorService->calculateSummary($netAmount, $couponCode);

        $this->assertSame('0.00', $summary->getDiscount());
        $this->assertSame(2300, $summary->getTax());
        $this->assertSame(0, $summary->getShipping());
    }
}
