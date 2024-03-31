<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Entity\Cart;

trait CartHelperTrait
{
    use ProtectedPropertyHelper;

    public function getHelperCart(int $id): Cart
    {
        return new Cart(userId: $id);
    }
}
