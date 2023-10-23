<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use DivisionByZeroError;

class TaxCalculator
{
    private int $taxRate = 23;
    private int|float $total = 0;
    private string $netAmount = "";
    private string $vatAmount = "";

    public function calculateOrderTax(Order $order): void
    {
        $this->calculateTax($order->getAmount());

        $order->setNetAmount(intval($this->getNetAmount()));
        $order->setVatAmount(intval($this->getVatAmount()));
    }

    /**
     * @param int $total
     * @return void
     */
    public function calculateTax(int|float $total): void
    {
        try {
            $this->total = $total;
            $vatDivisor = (string)(1 + ($this->taxRate / 100));
            $this->netAmount = bcdiv((string)$this->total, $vatDivisor);

            $this->vatAmount = bcsub((string)$this->total, $this->netAmount);
        } catch (DivisionByZeroError) {
            // no chance to throw this error, but we need to handle that for static analysis and coverage.
        }
    }

    public function getNetAmount(bool $humanFriendly = false): string
    {
        if ($humanFriendly) {
            return number_format(intval($this->netAmount) / 100, 2, '.', ' ');
        }

        return $this->netAmount;
    }

    public function getVatAmount(bool $humanFriendly = false): string
    {
        if ($humanFriendly) {
            return number_format(intval($this->vatAmount) / 100, 2, '.', ' ');
        }

        return $this->vatAmount;
    }
}
