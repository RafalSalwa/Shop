<?php

declare(strict_types=1);

namespace App\Enum;

use App\Entity\Product;
use App\Entity\Subscription;
use ValueError;

enum CartItemTypeEnum: string
{
    case product = Product::class;
    case subscription = Subscription::class;

    public static function fromName(string $name): string
    {
        foreach (self::cases() as $status) {
            if ($name === $status->name) {
                return $status->value;
            }
        }

        throw new ValueError($name . ' is not a valid backing value for enum ' . self::class);
    }

    public static function tryFromName(string $name): string|null
    {
        try {
            return self::fromName($name);
        } catch (ValueError) {
            return null;
        }
    }
}
