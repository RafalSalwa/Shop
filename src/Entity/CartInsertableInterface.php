<?php

declare(strict_types=1);

namespace App\Entity;

interface CartInsertableInterface
{
    public function toCartItem(): CartItem;

    public function getName(): string;
}
