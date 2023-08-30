<?php

namespace App\Factory;

use App\Entity\Cart;
use DateTime;
use DateTimeImmutable;
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
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTime());

        return $order;
    }
}
