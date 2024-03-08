<?php

declare(strict_types=1);

namespace App\Enum;

enum CouponType: string
{
    case CartDiscount = 'cart-discount';
    case ShippingDiscount = 'shipping-discount';
}
