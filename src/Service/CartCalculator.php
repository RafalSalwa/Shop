<?php

namespace App\Service;

use App\Entity\Cart;
use function bcdiv;

class CartCalculator
{
    private int $taxRate = 23;

    public function calculatePayment(Cart $cart): array
    {
        $total = 0;
        foreach ($cart->getItems() as $item) {
            $price = $item->getDestinationEntity()->getUnitPrice() * $item->getQuantity();
            $total += $price;
        }
        $vatDivisor = 1 + ($this->taxRate / 100);
        $netAmount = bcdiv($total, $vatDivisor, 0);
        $vatAmount = bcsub($total, $netAmount, 0);

        $total = number_format(($total / 100), 2, '.', ' ');
        $netAmount = number_format(($netAmount / 100), 2, '.', ' ');
        $vatAmount = number_format(($vatAmount / 100), 2, '.', ' ');

        return ["total" => $total, "vat" => $vatAmount, "net" => $netAmount];
    }
}