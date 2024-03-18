<?php

declare(strict_types=1);

namespace App\Enum;

enum CartOperationEnum: string
{
    case ADD_TO_CART = 'ADD_TO_CART';

    public static function addToCart(): string
    {
        return self::ADD_TO_CART->value;
    }
}
