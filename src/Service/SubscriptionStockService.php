<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Contracts\CartItemInterface;
use App\Entity\Product;
use App\Event\StockDepletedEvent;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Repository\ProductRepository;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use function is_null;
use function sprintf;

readonly final class SubscriptionStockService
{
    public function __construct(
        private LockFactory $productLockFactory,
        private ProductRepository $productRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * @throws ProductStockDepletedException
     * @throws ItemNotFoundException
     */
    public function checkStockIsAvailable(CartItemInterface $cartItem): void
    {
        if (false === $cartItem instanceof Product) {
            return;
        }

        $product = $this->productRepository->find($cartItem->getId());
        if (true === is_null($product)) {
            throw new ItemNotFoundException(sprintf('Product #%d not found.', $cartItem->getId()));
        }

        if (0 === $product->getUnitsInStock()) {
            throw new ProductStockDepletedException('For this product stock is depleted.');
        }
    }

    /** @phpstan-param Product::STOCK_* $operation */
    public function changeStock(CartItemInterface $cartItem, string $operation, int $qty = -1): void
    {
        if (false === $cartItem instanceof Product) {
            return;
        }

        match ($operation) {
            Product::STOCK_DECREASE => $this->decrease($cartItem),
            Product::STOCK_INCREASE => $this->increase($cartItem, $qty)
        };
    }

    private function decrease(CartItemInterface $cartItem): void
    {
        $sharedLock = $this->productLockFactory->createLock('product-stock_decrease');
        $sharedLock->acquire(true);
        if (1 === $cartItem->getUnitsInStock()) {
            $stockDepletedEvent = new StockDepletedEvent($cartItem);
            $this->eventDispatcher->dispatch($stockDepletedEvent);
        }

        $this->productRepository->decreaseQty($cartItem, 1);

        $sharedLock->release();
    }

    private function increase(CartItemInterface $cartItem, int $qty): void
    {
        $sharedLock = $this->productLockFactory->createLock('product-stock_decrease');
        $sharedLock->acquire(true);

        $this->productRepository->increaseQty($cartItem, $qty);
        $sharedLock->release();
    }
}
