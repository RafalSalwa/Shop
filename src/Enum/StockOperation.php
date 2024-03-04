<?php

declare(strict_types=1);

namespace App\Enum;

enum StockOperation: string
{
    case Increase = 'increase';
    case Decrease = 'decrease';
}
