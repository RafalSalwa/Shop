<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Enum\CartItemTypeEnum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use function is_string;

final class CartItemTypeResolver implements ValueResolverInterface
{
    /** @return array<class-string> */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ('type' !== $argument->getName()) {
            return [];
        }

        $value = $request->attributes->get($argument->getName());
        if (false === is_string($value)) {
            return [];
        }

        $entityType = CartItemTypeEnum::fromName($value);

        return [$entityType];
    }
}
