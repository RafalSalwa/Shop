<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Cart;
use App\Factory\CartFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    public function __construct(
        private readonly CartSessionStorage $cartSessionStorage,
        private readonly CartFactory $cartFactory,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function getCurrentCart(): Cart
    {
        $cart = $this->cartSessionStorage->getCart();
        if (!$cart instanceof \App\Entity\Cart) {
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
