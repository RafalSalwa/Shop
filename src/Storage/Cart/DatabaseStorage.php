<?php

declare(strict_types=1);

namespace App\Storage\Cart;

use App\Entity\Cart;
use App\Enum\CartStatus;
use App\Exception\CartOperationException;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Repository\CartRepository;
use App\Service\ProductStockService;
use App\Storage\Cart\Contracts\CartStorageInterface;

final readonly class DatabaseStorage implements CartStorageInterface
{
    public function __construct(
        private CartRepository $cartRepository,
        private ProductStockService $stockService,
    ) {
    }

    public function getCurrentCart(int $userId): Cart
    {
        $cart = $this->cartRepository->findOneBy(
            [
                'userId' => $userId,
                'status' => Cart::STATUS_CREATED,
            ],
            ['createdAt' => 'DESC'],
        );

        if (null === $cart) {
            $cart = new Cart($userId);
            $cart->setStatus(CartStatus::CREATED);
            $this->save($cart);
        }

        return $cart;
    }

    /** @throws CartOperationException */
    public function getCart(int $cartId): Cart
    {
        $cart = $this->cartRepository->find($cartId);
        if (null === $cart) {
            throw new CartOperationException('Cannot find cart with provided id');
        }

        return $cart;
    }

    public function save(Cart $cart): void
    {
        $this->cartRepository->save($cart);
    }

    public function confirm(Cart $cart): void
    {
        $cart->setStatus(CartStatus::CONFIRMED);
        $this->save($cart);
    }

    /**
     * @throws ItemNotFoundException
     * @throws ProductStockDepletedException
     */
    public function purge(Cart $cart): void
    {
        foreach ($cart->getItems() as $item) {
            $cart->removeItem($item);
            $this->stockService->restoreStock($item);
        }

        $cart->getItems()->clear();
        $this->save($cart);
    }
}
