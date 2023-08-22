<?php

namespace App\Factory;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\ProductCartItem;
use App\Entity\SubscriptionPlan;
use App\Entity\SubscriptionPlanCartItem;

class CartItemFactory
{
    public function createCartItem($entity)
    {
        return match (true) {
            $entity instanceof Product => $this->createProductCartItem($entity),
            $entity instanceof SubscriptionPlan => $this->createSubscriptionCartItem($entity),
            default => new CartItem(),
        };
    }

    private function createProductCartItem(Product $product, int $quantity = 1)
    {
        $cartItem = new ProductCartItem();
        $cartItem->setDestinationEntity($product);
        $cartItem->setQuantity($quantity);

        return $cartItem;
    }

    private function createSubscriptionCartItem(SubscriptionPlan $plan, int $quantity = 1)
    {
        $cartItem = new SubscriptionPlanCartItem();
        $cartItem->setDestinationEntity($plan);
        $cartItem->setQuantity($quantity);

        return $cartItem;
    }
}
