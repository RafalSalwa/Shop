<?php

declare(strict_types=1);

namespace App\Enum;

enum StockOperation: string
{
    case Increase = 'increase';
    case Decrease = 'decrease';

    public static function decrease(): string
    {
        return self::Decrease->value;
    }

    public static function increase(): string
    {
        return self::Increase->value;
    }
}
