<?php

declare(strict_types=1);

namespace App\Enum;

enum CartStatus: string
{
    case CREATED = 'created';

    case CONFIRMED = 'confirmed';

    case CANCELLED = 'cancelled';
}
