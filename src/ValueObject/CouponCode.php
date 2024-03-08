<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Enum\CouponType;

final readonly class CouponCode
{
    public function __construct(private string $type, private string $value)
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isCartDiscount(): bool
    {
        return $this->type === CouponType::CartDiscount->value;
    }

    public function isShippingDiscount(): bool
    {
        return $this->type === CouponType::ShippingDiscount->value;
    }
}
