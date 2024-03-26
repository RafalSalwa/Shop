<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Contracts\CartItemInterface;
use App\Exception\InsufficientStockException;
use App\Exception\ItemNotFoundException;
use App\Factory\CartItemFactory;

final readonly class CartItemService
{
    public function __construct(
        private CartItemFactory $cartItemFactory,
    ) {}

    /**
     * @throws InsufficientStockException
     * @throws ItemNotFoundException
     */
    public function create(int $id, int $quantity): CartItemInterface
    {
        return $this->cartItemFactory->create($id, $quantity);
    }
}
