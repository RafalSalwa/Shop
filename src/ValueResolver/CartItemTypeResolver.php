<?php

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
        if ($argument->getName() !== "type") {
            return [];
        }
        // get the value from the request, based on the argument name
        $value = $request->attributes->get($argument->getName());
        if (!is_string($value)) {
            return [];
        }
        $entityTypeMap = [
            'product' => Product::class,
            'plan' => SubscriptionPlan::class,
        ];
        if (!isset($entityTypeMap[$value])) {
            throw new InvalidArgumentException("Unknown entity type:" . $value);
        }

        return [$entityTypeMap[$value]];
    }
}
