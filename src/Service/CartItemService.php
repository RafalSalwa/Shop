<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Contracts\CartItemInterface;
use App\Exception\CartOperationException;
use App\Exception\InsufficientStockException;
use App\Exception\ItemNotFoundException;
use App\Factory\CartItemFactory;
use App\Repository\CartItemRepository;

final readonly class CartItemService
{
    public function __construct(
        private CartItemFactory $cartItemFactory,
        private CartItemRepository $cartItemRepository,
    ) {}

    /**
     * @throws InsufficientStockException
     * @throws ItemNotFoundException
     */
    public function create(Cart $cart, int $itemId, int $quantity): CartItemInterface
    {
        return $this->cartItemFactory->create($cart, $itemId, $quantity);
    }

    /** @throws CartOperationException */
    public function getItem(int $itemId): CartItemInterface
    {
        $item = $this->cartItemRepository->find($itemId);
        if (null === $item) {
            throw new CartOperationException('Cannot find item with such id');
        }

        return $item;
    }

    /** @throws ItemNotFoundException */
    public function removeItem(CartItemInterface $cartItem): void
    {
        $cart = $cartItem->getCart();
        if (false === $cart->hasItem($cartItem)) {
            throw new ItemNotFoundException('Item already removed');
        }

        $cart->removeItem($cartItem);
    }
}
