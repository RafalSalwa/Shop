<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\InvalidCouponCodeException;
use App\ValueObject\CouponCode;

use function array_filter;
use function array_key_exists;

use const ARRAY_FILTER_USE_KEY;

final class CouponService
{
    /** @throws InvalidCouponCodeException */
    public function getCouponType(string $couponCode): CouponCode
    {
        if (false === $this->isCodeValid($couponCode)) {
            throw new InvalidCouponCodeException('This code cannot be applied');
        }

        return $this->getCode($couponCode);
    }

    private function isCodeValid(string $couponCode): bool
    {
        return array_key_exists($couponCode, $this->getAvailableCodes());
    }

    /** @return array<string, array<string,string>> */
    private function getAvailableCodes(): array
    {
        return [
            'discount10' => [
                'type' => 'cart-discount',
                'value' => '10',
            ],
            'freeshipping' => [
                'type' => 'shipping-discount',
                'value' => '100',
            ],
        ];
    }

    private function getCode(string $name): CouponCode
    {
        $coupon = array_filter(
            $this->getAvailableCodes(),
            static fn (string $code): bool => $code === $name,
            ARRAY_FILTER_USE_KEY,
        );

        return new CouponCode(type: $coupon[$name]['type'], value: $coupon[$name]['value']);
    }
}
