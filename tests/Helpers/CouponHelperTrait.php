<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\ValueObject\CouponCode;

trait CouponHelperTrait
{
    protected function getHelperCartCoupon(): CouponCode
    {
        return new CouponCode('cart-discount', '10');
    }

    protected function getHelperShipmentCoupon(): CouponCode
    {
        return new CouponCode('shipment-discount', '100');
    }
}
