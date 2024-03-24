<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use InvalidArgumentException;

use function array_key_exists;

final class EntityNameResolver
{
    public function resolve(string $entityType): string
    {
        $entitiesMap = [
            'product' => Product::class,
            'plan' => SubscriptionPlan::class,
        ];

        if (false === array_key_exists($entityType, $entitiesMap)) {
            throw new InvalidArgumentException('Unknown entity type: ' . $entityType);
        }

        return $entitiesMap[$entityType];
    }
}
