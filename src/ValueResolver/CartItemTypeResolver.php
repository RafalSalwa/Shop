<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Enum\CartItemTypeEnum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use function is_string;

class CartItemTypeResolver implements ValueResolverInterface
{
    /** @return array<int, class-string> */
    public function resolve(Request $request, ArgumentMetadata $argumentMetadata): iterable
    {
        if ('type' !== $argumentMetadata->getName()) {
            return [];
        }

        $value = $request->attributes->get($argumentMetadata->getName());
        if (! is_string($value)) {
            return [];
        }

        $entityType = CartItemTypeEnum::fromName($value);

        return [$entityType];
    }
}
