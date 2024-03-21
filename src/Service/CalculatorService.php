<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\CouponCode;
use App\ValueObject\Summary;
use function bcadd;
use function bcdiv;
use function bcmul;
use function bcsub;
use function is_null;

final readonly class CalculatorService
{
    public const FIRST_DISCOUNT_LIMIT = 50_00;
    public const FREE_DELIVERY_LIMIT = 150_00;

    public function __construct(private string $taxRate = '23')
    {
    }

    public function calculateSummary(int $netAmount, ?CouponCode $coupon): Summary
    {
        $discount = $this->calculateDiscount($netAmount, $coupon);
        $netTotal = $this->calculateNetTotal($netAmount, $discount);
        $tax = $this->calculateTax($netTotal);

        $subTotal = $this->calculateSubTotal($netTotal, $tax);
        $shipping = $this->calculateShipping($subTotal, $coupon);

        return new Summary(net: $netAmount, discount: $discount, tax: $tax, shipping: $shipping);
    }

    private function calculateDiscount(int $netAmount, ?CouponCode $coupon): int
    {
        if (null === $coupon) {
            return 0;
        }
        if (false === $coupon->isCartDiscount()) {
            return 0;
        }
        $discount = bcdiv($coupon->getValue(), '100', 2);

        return (int)bcmul((string)$netAmount, $discount, 2);
    }

    private function calculateNetTotal(int $netAmount, int $discount): int
    {
        return (int)bcsub((string)$netAmount, (string)$discount);
    }

    private function calculateTax(int $subTotal): int
    {
        $taxDivisor = bcdiv($this->taxRate, '100', 2);

        return (int)bcmul((string)$subTotal, $taxDivisor, 2);
    }

    private function calculateSubTotal(int $netTotal, int $tax): int
    {
        return (int)bcadd((string)$netTotal, (string)$tax);
    }

    private function calculateShipping(int $amount, ?CouponCode $coupon): int
    {
        if (false === is_null($coupon) && true === $coupon->isShippingDiscount()) {
            return 0;
        }

        return match (true) {
            $amount >= self::FREE_DELIVERY_LIMIT => 0,
            $amount >= self::FIRST_DISCOUNT_LIMIT =>10_00,
            default => 20_00,
        };
    }
}
