<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\InvalidCouponCodeException;
use App\ValueObject\CouponCode;

use function array_key_exists;

final class CouponService
{
    /** @throws InvalidCouponCodeException */
    public function getCouponType(string $couponCode): CouponCode
    {
        if (false === $this->isCodeValid($couponCode)) {
            throw new InvalidCouponCodeException('This code cannot be applied');
        }

        $codes = $this->getAvailableCodes();
        $code = $codes[$couponCode];

        return new CouponCode(type: $code['type'], value: $code['value']);
    }

    private function isCodeValid(string $couponCode): bool
    {
        return array_key_exists($couponCode, $this->getAvailableCodes());
    }

    /** @return array{
     *     discount10: array{type: 'cart-discount', value: 10},
     *     freeshipping: array{type: 'shipping-discount', value: 100}}
     */
    private function getAvailableCodes(): array
    {
        return [
            'discount10' => [
                'type' => 'cart-discount',
                'value' => 10,
            ],
            'freeshipping' => [
                'type' => 'shipping-discount',
                'value' => 100,
            ],
        ];
    }
}
