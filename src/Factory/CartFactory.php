<?php

namespace App\Factory;

use App\Entity\Cart;
use App\Entity\CartInsertableInterface;
use App\Entity\CartItem;
use App\Entity\Plan;
use App\Entity\Product;
use App\Entity\ProductCartItem;
use App\Entity\SubscriptionCartItem;
use Symfony\Bundle\SecurityBundle\Security;

class CartFactory
{
    public function __construct(private readonly Security $security)
    {
    }

    public function create(): Cart
    {
        $order = new Cart();
        $order
            ->setStatus(Cart::STATUS_CREATED)
            ->setUser($this->security->getUser())
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        return $order;
    }

    /**
     * Creates an item for a product.
     */
    public function createItem(CartInsertableInterface $item): CartItem
    {
        return match ($item::class) {
            Plan::class => new SubscriptionCartItem(),
            Product::class => new ProductCartItem(),
            default => new CartItem()
        };
    }
}
