<?php

namespace App\Manager;

use App\Entity\Cart;
use App\Factory\CartFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    private CartSessionStorage $cartSessionStorage;
    private EntityManagerInterface $entityManager;
    private CartFactory $cartFactory;

    public function __construct(
        CartSessionStorage $cartStorage,
        CartFactory $orderFactory,
        EntityManagerInterface $entityManager
    ) {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->entityManager = $entityManager;
    }

    public function getCurrentCart(): Cart
    {
        $cart = $this->cartSessionStorage->getCart();
        if (!$cart) {
            $cart = $this->cartFactory->create();
        }

        return $cart;
    }

    /**
     * Persists the cart in database and session.
     */
    public function save(Cart $cart): void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        $this->cartSessionStorage->setCart($cart);
    }
}
