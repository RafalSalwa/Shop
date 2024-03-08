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

final readonly class CartCalculatorService
{
    public const FIRST_DISCOUNT_LIMIT = 100;
    public const FREE_DELIVERY_LIMIT = 300;

    public function __construct(private CartService $cartService, private ?string $taxRate = '23')
    {
    }

    public function calculateSummary(): Summary
    {
        $cart = $this->cartService->getCurrentCart();

        $netAmount = $cart->getTotalAmount();
        $discount = $this->calculateDiscount($netAmount, $cart->getCoupon());
        $netTotal = bcsub($netAmount, $discount);
        $tax = $this->calculateTaxAmount($netTotal);

        $subTotal = bcadd($netTotal, $tax);
        $shipping = $this->calculateShipping($subTotal, $cart->getCoupon());

        return new Summary(net: $netAmount, discount: $discount, tax: $tax, shipping: $shipping);
    }

    private function calculateDiscount(string $netAmount, ?CouponCode $coupon): string
    {
        if (true === is_null($coupon)) {
            return '0';
        }
        if (false === $coupon->isCartDiscount()) {
            return '0';
        }
        $discount = bcdiv($coupon->getValue(), '100', 2);

        return bcmul($netAmount, $discount, 2);
    }

    private function calculateTaxAmount(string $subTotal): string
    {
        $taxDivisor = bcdiv($this->taxRate, '100', 2);

        return bcmul($subTotal, $taxDivisor, 2);
    }

    public function calculateShipping(string $amount, ?CouponCode $coupon): string
    {
        if (        false === is_null($coupon)
            && true === $coupon->isShippingDiscount()
        ) {
            return '0';
        }

        $total = (int)bcdiv($amount, '100', 2);

        return match (true) {
            $total >= self::FREE_DELIVERY_LIMIT => '0',
            $total >= self::FIRST_DISCOUNT_LIMIT =>'10',
            default => '20',
        };
    }
}
