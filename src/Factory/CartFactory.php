<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Cart;
use App\Enum\CartStatus;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CartFactory
{
    public function __construct(private Security $security)
    {
    }

    public function create(): Cart
    {
        $cart = new Cart();
        $cart->setStatus(CartStatus::CREATED);
        $cart->setUserId($this->security->getUser()->getId());

        return $cart;
    }
}
