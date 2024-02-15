<?php

declare(strict_types=1);

namespace App\Enum;

use App\Entity\Product;
use App\Entity\Subscription;

enum CartItemTypeEnum: string
{
    case product = Product::class;
    case subscription = Subscription::class;

}
