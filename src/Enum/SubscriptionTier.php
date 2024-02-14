<?php

declare(strict_types=1);

namespace App\Enum;

enum SubscriptionTier: int
{
    case Freemium = 1;
    case Plus = 2;
    case Gold = 3;
    case VIP = 4;
    case FIVE_HUNDRED = 5;
    case EIGHT_HUNDRED = 6;

    public function getLabel(): void {}
}
