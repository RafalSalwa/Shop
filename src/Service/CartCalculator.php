<?php

namespace App\Service;

use App\Entity\Cart;

class CartCalculator
{
    public function __construct(private readonly TaxCalculator $taxCalculator)
    {
    }

    public function calculatePayment(Cart $cart): array
    {
        $total = $this->calculateTotal($cart);
        $this->taxCalculator->calculateTax($total);

        return [
            "total" => $total,
            "vat" => $this->taxCalculator->getNetAmount(),
            "net" => $this->taxCalculator->getNetAmount()];
    }

    public function calculateTotal(Cart $cart)
    {
        $total = 0;
        foreach ($cart->getItems() as $item) {
            $price = $item->getDestinationEntity()->getUnitPrice() * $item->getQuantity();
            $total += $price;
        }
        return $total;
    }
}