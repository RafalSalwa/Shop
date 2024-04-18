<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Exception\InvalidCouponCodeException;
use App\Service\CouponService;
use App\ValueObject\CouponCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: CouponService::class)]
#[UsesClass(className: CouponCode::class)]
final class CouponServiceTest extends TestCase
{
    private CouponService $couponService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an instance of CouponService
        $this->couponService = new CouponService();
    }

    public function testGetCouponTypeWithValidCode(): void
    {
        // Define a valid coupon code
        $validCode = 'discount10';

        // Call the getCouponType method with the valid code
        $coupon = $this->couponService->getCouponType($validCode);

        // Assert that the returned coupon is of type CouponCode
        $this->assertInstanceOf(CouponCode::class, $coupon);
        // Assert that the type and value of the returned coupon are as expected
        $this->assertSame('cart-discount', $coupon->getType());
        $this->assertSame(10, $coupon->getValue());
    }

    public function testGetCouponTypeWithInvalidCode(): void
    {
        // Define an invalid coupon code
        $invalidCode = 'invalidcode';

        // Expect an InvalidCouponCodeException to be thrown
        $this->expectException(InvalidCouponCodeException::class);

        // Call the getCouponType method with the invalid code
        $this->couponService->getCouponType($invalidCode);
    }
}
