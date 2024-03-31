<?php

declare(strict_types=1);

namespace App\Storage\Cart\Contracts;

use App\Entity\Cart;

interface CartStorageInterface
{
    public function getCurrentCart(int $userId): Cart;

    public function getCart(int $cartId): Cart;

    public function save(Cart $cart): void;

    public function confirm(Cart $cart): void;

    public function purge(Cart $cart): void;
}
