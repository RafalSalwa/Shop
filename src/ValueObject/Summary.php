<?php

declare(strict_types=1);

namespace App\ValueObject;

use function bcadd;
use function bcdiv;
use function bcsub;

final readonly class Summary
{
    public function __construct(
        private string $net,
        private string $discount,
        private string $tax,
        private string $shipping,
    ) {
    }

    public function getNet(): string
    {
        return bcdiv($this->net, '100', 2);
    }

    public function getTax(): string
    {
        return bcdiv($this->tax, '100', 2);
    }

    public function getShipping(): string
    {
        return $this->shipping;
    }

    public function getTotal(): string
    {
        return bcadd($this->getSubTotal(), $this->shipping, 2);
    }

    public function getSubTotal(): string
    {
        $subTotal = $this->net;
        if ('0' !== $this->discount) {
            $subTotal = bcsub($this->net, $this->discount, 2);
        }

        $subTotal = bcadd($subTotal, $this->tax, 2);

        return bcdiv($subTotal, '100', 2);
    }

    public function getDiscount(): string
    {
        return bcdiv($this->discount, '100', 2);
    }
}
