<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\CartItem;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\ProductCartItem;
use App\Enum\CartOperationEnum;
use App\Exception\InsufficientStockException;
use App\Exception\ItemNotFoundException;
use App\Service\ProductsService;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use function sprintf;

final readonly class CartItemFactory
{
    public function __construct(
        private ProductsService $service,
        private AuthorizationCheckerInterface $addToCartVoter,
    ) {
    }

    /**
     * @throws ItemNotFoundException
     * @throws InsufficientStockException
     * @throws AccessDeniedException
     */
    public function create(int $id, int $quantity): CartItemInterface
    {
        $product = $this->service->byId($id);
        if (null === $product) {
            throw new ItemNotFoundException(sprintf('Product #%s not found', $id));
        }

        if ($product->getUnitsInStock() < $quantity) {
            throw new InsufficientStockException(sprintf('Product #%s does not have sufficient stock', $id));
        }

        if (false === $this->addToCartVoter->isGranted(CartOperationEnum::addToCart(), $product)) {
            throw new AccessDeniedException('Higher subscription required');
        }

        return new CartItem(referencedEntity: $product, quantity: $quantity);
    }
}
