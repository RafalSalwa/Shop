<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;

class CartItemTypeResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ('type' !== $argument->getName()) {
            return [];
        }

        $value = $request->attributes->get($argument->getName());
        if (!\is_string($value)) {
            return [];
        }
        $entityTypeMap = [
            'product' => Product::class,
            'plan' => SubscriptionPlan::class,
        ];
        if (!isset($entityTypeMap[$value])) {
            throw new InvalidArgumentException('Unknown entity type:'.$value);
        }

        return [$entityTypeMap[$value]];
    }
}
