<?php

declare(strict_types=1);

namespace App\Enum;

enum SubscriptionTier: int
{
    case Freemium = 1;
    case Plus = 10;
    case Gold = 20;
    case VIP = 30;
    case FIVE_HUNDRED = 50;
    case EIGHT_HUNDRED = 80;

    public function value(): int
    {
        return $this->value;
    }
}
