<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Cart;
use App\Enum\CartStatus;

final readonly class CartFactory
{
    public function create(int $userId): Cart
    {
        $cart = new Cart($userId);
        $cart->setStatus(CartStatus::CREATED);

        return $cart;
    }
}
