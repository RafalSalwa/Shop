<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;

final readonly class CartCalculatorService
{
    public function __construct(
        private TaxCalculatorService $taxCalculator
    ) {}

    public function calculatePayment(Cart $cart): array
    {
        $total = $this->calculateTotal($cart);
        $this->taxCalculator->calculateTax($total);

        return [
            'total' => $total,
            'vat' => $this->taxCalculator->getNetAmount(),
            'net' => $this->taxCalculator->getNetAmount(),
        ];
    }

    public function calculateTotal(Cart $cart): float|int
    {
        $total = 0;

        /** @var CartItem $item */
        foreach ($cart->getItems() as $item) {
            $price = $item->getReferencedEntity()
                ->getPrice() * $item->getQuantity()
            ;
            $total += $price;
        }

        return $total;
    }
}
