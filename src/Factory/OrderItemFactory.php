<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Contracts\CartItemInterface;
use App\Entity\Order;
use App\Entity\OrderItem;

final class OrderItemFactory
{
    public static function createFromCartItem(CartItemInterface $cartItem, Order $order): OrderItem
    {
        return new OrderItem(
            prodId: $cartItem->getReferencedEntity()->getId(),
            quantity: $cartItem->getQuantity(),
            price: $cartItem->getReferencedEntity()->getPrice(),
            name: $cartItem->getReferencedEntity()->getName(),
            itemType: $cartItem->getType(),
            order: $order,
        );
    }
}
