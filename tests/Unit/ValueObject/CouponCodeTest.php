<?php

declare(strict_types=1);

namespace App\Tests\Unit\ValueObject;

use App\Enum\CouponType;
use App\ValueObject\CouponCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: CouponCode::class)]
final class CouponCodeTest extends TestCase
{
    public function testGetType(): void
    {
        $couponCode = new CouponCode(CouponType::CartDiscount->value, 10);
        $this->assertSame(CouponType::CartDiscount->value, $couponCode->getType());
    }

    public function testGetValue(): void
    {
        $couponCode = new CouponCode(CouponType::CartDiscount->value, 10);
        $this->assertSame(10, $couponCode->getValue());
    }

    public function testIsCartDiscount(): void
    {
        $cartCoupon = new CouponCode(CouponType::CartDiscount->value, 10);
        $shippingCoupon = new CouponCode(CouponType::ShippingDiscount->value, 10);

        $this->assertTrue($cartCoupon->isCartDiscount());
        $this->assertFalse($shippingCoupon->isCartDiscount());
    }

    public function testIsShippingDiscount(): void
    {
        $cartCoupon = new CouponCode(CouponType::CartDiscount->value, 10);
        $shippingCoupon = new CouponCode(CouponType::ShippingDiscount->value, 10);

        $this->assertFalse($cartCoupon->isShippingDiscount());
        $this->assertTrue($shippingCoupon->isShippingDiscount());
    }
}
