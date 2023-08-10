<?php

namespace App\Entity;

interface CartInsertableInterface
{
    public function toCartItem(): CartItem;
}
