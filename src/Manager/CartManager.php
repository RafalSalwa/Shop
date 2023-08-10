<?php

namespace App\Manager;

use App\Entity\Cart;
use App\Factory\CartFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CartManager
{
    private CartSessionStorage $cartSessionStorage;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(
        CartSessionStorage $cartStorage,
        CartFactory $orderFactory,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->entityManager = $entityManager;
        $this->security = $security;
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
        // Persist in database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }
}
