<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Contracts\CartItemInterface;
use App\Entity\OrderItem;

final class OrderItemFactory
{
    public static function createFromCartItem(CartItemInterface $cartItem): OrderItem
    {
        $orderItem = new OrderItem(
            prodId: $cartItem->getReferencedEntity()->getId(),
            quantity: $cartItem->getQuantity(),
            price: $cartItem->getReferencedEntity()->getPrice(),
            name: $cartItem->getReferencedEntity()->getName(),
        );
        $orderItem->setItemType($cartItem->getType());

        return $orderItem;
    }
}
