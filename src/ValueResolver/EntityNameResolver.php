<?php

namespace App\ValueResolver;

use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use InvalidArgumentException;

class EntityNameResolver
{
    public function resolve(string $entityType): string
    {
        $entityTypeMap = [
            'product' => Product::class,
            'plan' => SubscriptionPlan::class,
        ];

        if (!isset($entityTypeMap[$entityType])) {
            throw new InvalidArgumentException("Unknown entity type: {$entityType}");
        }

        return $entityTypeMap[$entityType];
    }
}
