<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Service\CalculatorService;
use App\ValueObject\CouponCode;
use App\ValueObject\Summary;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CalculatorService::class)]
#[UsesClass(className: Summary::class)]
#[UsesClass(className: CouponCode::class)]
final class CalculatorServiceTest extends TestCase
{
    private CalculatorService $calculatorService;

    protected function setUp(): void
    {
        $this->calculatorService = new CalculatorService();
    }

    public function testCalculateSummaryWithoutCoupon(): void
    {
        $netAmount = 100_00;
        $expectedSummary = new Summary(net: $netAmount, discount: 0, tax: 23_00, shipping: 10_00);

        $summary = $this->calculatorService->calculateSummary($netAmount, null);

        $this->assertSame($expectedSummary->getDiscount(), $summary->getDiscount());
        $this->assertSame($expectedSummary->getNet(), $summary->getNet());
        $this->assertSame($expectedSummary->getShipping(), $summary->getShipping());
        $this->assertSame($expectedSummary->getSubTotal(), $summary->getSubTotal());
        $this->assertSame($expectedSummary->getTotal(), $summary->getTotal());
    }

    public function testCalculateSummaryWithPercentageCoupon(): void
    {
        $netAmount = 60_00;
        $couponCode = new CouponCode(type: 'cart-discount', value: 20);

        $expectedDiscount = 12_00;
        $expectedSummary = new Summary(net: $netAmount, discount: $expectedDiscount, tax: 11_04, shipping: 10_00);

        $summary = $this->calculatorService->calculateSummary($netAmount, $couponCode);

        $this->assertSame($expectedSummary->getDiscount(), $summary->getDiscount());
        $this->assertSame($expectedSummary->getNet(), $summary->getNet());
        $this->assertSame($expectedSummary->getShipping(), $summary->getShipping());
        $this->assertSame($expectedSummary->getSubTotal(), $summary->getSubTotal());
        $this->assertSame($expectedSummary->getTotal(), $summary->getTotal());
    }

    public function testCalculateSummaryWithFixedAmountCoupon(): void
    {
        $netAmount = 100_00;
        $couponCode = new CouponCode(type: 'cart-discount', value: 50);

        $expectedSummary = new Summary(net: $netAmount, discount: 50_00, tax: 11_50, shipping: 10_00);

        $summary = $this->calculatorService->calculateSummary($netAmount, $couponCode);

        $this->assertSame($expectedSummary->getDiscount(), $summary->getDiscount());
        $this->assertSame($expectedSummary->getNet(), $summary->getNet());
        $this->assertSame($expectedSummary->getShipping(), $summary->getShipping());
        $this->assertSame($expectedSummary->getSubTotal(), $summary->getSubTotal());
        $this->assertSame($expectedSummary->getTotal(), $summary->getTotal());
    }
}
