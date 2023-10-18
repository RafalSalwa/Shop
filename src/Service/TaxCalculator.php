<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;

class TaxCalculator
{
    private int $taxRate = 23;
    private int $total;
    private string $netAmount;
    private string $vatAmount;

    public function calculateOrderTax(Order $order)
    {
        $this->calculateTax($order->getAmount());
        $order->setNetAmount($this->getNetAmount(false));
        $order->setVatAmount($this->getVatAmount(false));
    }

    public function calculateTax(int $total)
    {
        $this->total = $total;
        $vatDivisor = 1 + ($this->taxRate / 100);
        $this->netAmount = bcdiv($this->total, $vatDivisor, 0);
        $this->vatAmount = bcsub($this->total, $this->netAmount, 0);
    }

    public function getNetAmount(bool $humanFriendly = false)
    {
        if ($humanFriendly) {
            return number_format($this->netAmount / 100, 2, '.', ' ');
        }

        return $this->netAmount;
    }

    public function getVatAmount(bool $humanFriendly = false)
    {
        if ($humanFriendly) {
            return number_format($this->vatAmount / 100, 2, '.', ' ');
        }

        return $this->vatAmount;
    }
}
