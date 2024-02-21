<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CartItemInterface;
use App\Entity\Product;
use App\Entity\StockManageableInterface;
use App\Event\StockDepletedEvent;
use App\Exception\ProductStockDepletedException;
use App\Repository\ProductRepository;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProductStockService
{
    public function __construct(
        private readonly LockFactory $productLockFactory,
        private readonly ProductRepository $productRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {}

    /** @throws ProductStockDepletedException */
    public function checkStockIsAvailable(CartItemInterface $cartItem): void
    {
        $cartInsertable = $cartItem->getReferenceEntity();
        if ($cartInsertable instanceof StockManageableInterface && 0 === $cartInsertable->getUnitsInStock()) {
            throw new ProductStockDepletedException('For this product stock is depleted.');
        }
    }

    public function restoreStock(CartItemInterface $cartItem): void
    {
        $this->changeStock($cartItem, Product::STOCK_INCREASE, $cartItem->getQuantity());
    }

    /**
     * @psalm-param Product::STOCK_* $operation
     */
    public function changeStock(CartItemInterface $cartItem, string $operation, int $qty): void
    {
        $cartInsertable = $cartItem->getReferencedEntity();
        if (! $cartInsertable instanceof StockManageableInterface) {
            return;
        }

        match ($operation) {
            Product::STOCK_DECREASE => $this->decrease($cartInsertable, $qty),
            Product::STOCK_INCREASE => $this->increase($cartInsertable, $qty)
        };
    }

    /** @throws ProductStockDepletedException */
    private function decrease(\App\Entity\CartInsertableInterface $cartInsertable, int $qty): void
    {
        $sharedLock = $this->productLockFactory->createLock('product-stock_decrease');
        $sharedLock->acquire(true);

        if (0 === $cartInsertable->getUnitsInStock()) {
            throw new ProductStockDepletedException();
        }

        if (1 === $cartInsertable->getUnitsInStock()) {
            $stockDepletedEvent = new StockDepletedEvent($cartInsertable);
            $this->eventDispatcher->dispatch($stockDepletedEvent);
        }

        $this->productRepository->decreaseQty($cartInsertable, $qty);

        $sharedLock->release();
    }

    private function increase(\App\Entity\CartInsertableInterface $cartInsertable, int $qty): void
    {
        $sharedLock = $this->productLockFactory->createLock('product-stock_decrease');
        $sharedLock->acquire(true);

        $this->productRepository->increaseQty($cartInsertable, $qty);
        $sharedLock->release();
    }
}
