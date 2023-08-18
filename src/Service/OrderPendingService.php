<?php

namespace App\Service;

use App\Factory\CartFactory;
use App\Factory\CartItemFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class OrderPendingService
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

    public function createPending()
    {

    }
}
