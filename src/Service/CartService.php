<?php

namespace App\Service;

use App\Entity\Cart;
use App\Factory\CartFactory;
use App\Factory\CartItemFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CartService
{
    private CartSessionStorage $cartSessionStorage;
    private EntityManagerInterface $entityManager;
    private CartFactory $cartFactory;
    private Security $security;
    private CartItemFactory $cartItemFactory;

    public function __construct(
        CartSessionStorage     $cartStorage,
        CartFactory            $orderFactory,
        EntityManagerInterface $entityManager,
        Security               $security,
        CartItemFactory        $cartItemFactory
    )
    {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->cartItemFactory = $cartItemFactory;
    }

    public function makeCartItem($entity)
    {
        return $this->cartItemFactory->createCartItem($entity);
    }

    /**
     * Persists the cart in database and session.
     */
    public function save(Cart $cart = null): void
    {
        if ($cart) {
            $cart = $this->getCurrentCart();
        }
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        $this->cartSessionStorage->setCart($cart);
    }

    public function getCurrentCart(): Cart
    {
        $cart = $this->cartSessionStorage->getCart();
        if (!$cart) {
            $cart = $this->cartFactory->create();
        }

        return $cart;
    }
}
