<?php

namespace App\Factory;

use App\Entity\Cart;
use DateTime;
use DateTimeImmutable;
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
        $order = new Cart();
        $order
            ->setStatus(Cart::STATUS_CREATED)
            ->setUser($this->security->getUser())
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTime());

        return $order;
    }
}
