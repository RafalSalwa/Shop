<?php

declare(strict_types=1);

namespace App\Service;

use App\ValueObject\CouponCode;

final class TaxCalculatorService
{
    public function __construct(
        private readonly string $netAmount,
        private readonly ?CouponCode $coupon,
        private readonly string $taxRate = '23',
    ) {
    }
}
