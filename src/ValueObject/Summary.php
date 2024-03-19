<?php

declare(strict_types=1);

namespace App\ValueObject;

use function bcadd;
use function bcsub;

final readonly class Summary
{
    public function __construct(private int $net, private int $discount, private int $tax, private int $shipping)
    {
    }

    public function getShipping(): int
    {
        return $this->shipping;
    }

    public function getTotal(): int
    {
        return (int)bcadd((string)$this->getSubTotal(), (string)$this->shipping, 2);
    }

    public function getSubTotal(): int
    {
        $subTotal = $this->net;
        if (0 !== $this->discount) {
            $subTotal = bcsub((string)$this->net, (string)$this->discount, 2);
        }

        return (int)bcadd((string)$subTotal, (string)$this->tax);
    }
}
