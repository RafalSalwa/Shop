<?php

declare(strict_types=1);

namespace App\Enum;

enum CartOperationEnum: string
{
    case ADD = 'add';

    public static function addToCart(): string
    {
        return self::ADD->value;
    }
}
