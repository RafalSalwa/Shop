<?php

namespace App\Factory;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
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
    public function createItem(Product $product): CartItem
    {
        $item = new CartItem();
        $item->setProdId($product);
        $item->setQuantity(1);

        return $item;
    }
}
