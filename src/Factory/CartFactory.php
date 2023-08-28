<?php

namespace App\Factory;

use App\Entity\Cart;
use DateTime;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class CartFactory
{
    public function __construct(private readonly Security     $security,
                                private readonly RequestStack $requestStack)
    {
    }

    public function create(): Cart
    {
        dd($this->requestStack->getCurrentRequest()->getUser());
        $order = new Cart();
        $order
            ->setStatus(Cart::STATUS_CREATED)
            ->setUser($this->security->getUser())
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        return $order;
    }
}
