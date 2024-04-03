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
        // 10.00
        $netAmount = 10_00;
        $coupon = null;

        $summary = $calculatorService->calculateSummary($netAmount, $coupon);

        $this->assertSame('0.00', $summary->getDiscount());
        $this->assertSame(2_30, $summary->getTax());
        $this->assertSame(20_00, $summary->getShipping());
    }

    public function testCalculateSummaryCartDiscount(): void
    {
        $calculatorService = new CalculatorService();
        // 10.00
        $netAmount = 10_00;
        $couponCode = new CouponCode('cart-discount', 10);

        $summary = $calculatorService->calculateSummary($netAmount, $couponCode);

        $this->assertSame('1.00', $summary->getDiscount());
        $this->assertSame(2_07, $summary->getTax());
        $this->assertSame(20_00, $summary->getShipping());
    }

    public function testCalculateSummaryShippingDiscount(): void
    {
        $calculatorService = new CalculatorService();
        // 100.00
        $netAmount = 100_00;
        $couponCode = new CouponCode('shipping-discount', 1_00);

        $summary = $calculatorService->calculateSummary($netAmount, $couponCode);

        $this->assertSame('0.00', $summary->getDiscount());
        $this->assertSame(23_00, $summary->getTax());
        $this->assertSame(0, $summary->getShipping());
    }
}
