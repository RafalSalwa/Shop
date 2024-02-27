<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Cart;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CartFactory
{
    public function __construct(private Security $security)
    {}

    public function create(): Cart
    {
        $cart = new Cart();
        $cart->setStatus(Cart::STATUS_CREATED);
        $cart->setUserId($this->security->getUser()->getId());
        $cart->setCreatedAt(new DateTimeImmutable());
        $cart->setUpdatedAt(new DateTime());

        return $cart;
    }
}
