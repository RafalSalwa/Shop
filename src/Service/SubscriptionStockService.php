<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CartItemInterface;
use App\Entity\Product;
use App\Event\StockDepletedEvent;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Repository\ProductRepository;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

readonly class SubscriptionStockService
{
    public function __construct(
        private LockFactory $productLockFactory,
        private ProductRepository $repository,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws ProductStockDepletedException
     * @throws ItemNotFoundException
     */
    public function checkStockIsAvailable(CartItemInterface $entity): void
    {
        if ($entity instanceof Product) {
            $product = $this->repository->find($entity->getId());
            if (!$product) {
                throw new ItemNotFoundException(sprintf('Product #%d not found.', $entity->getId()));
            }
            if ($product->getUnitsInStock() === 0) {
                throw new ProductStockDepletedException('For this product stock is depleted.');
            }
        }
    }

    public function restoreStock(CartItemInterface $item, string $STOCK_INCREASE): void
    {
        $this->changeStock($item, Product::STOCK_INCREASE, $item->getQuantity());
    }

    /** @phpstan-param Product::STOCK_* $operation */
    public function changeStock(CartItemInterface $entity, string $operation, int $qty = -1): void
    {
        if ($entity instanceof Product) {
            match ($operation) {
                Product::STOCK_DECREASE => $this->decrease($entity),
                Product::STOCK_INCREASE => $this->increase($entity, $qty)
            };
        }
    }

    private function decrease(Product $product): void
    {
        $lock = $this->productLockFactory->createLock('product-stock_decrease');
        $lock->acquire(true);
        if ($product->getUnitsInStock() === 1) {
            $event = new StockDepletedEvent($product);
            $this->eventDispatcher->dispatch($event);
        }
        $this->repository->decreaseQty($product, 1);

        $lock->release();
    }

    private function increase(Product $product, int $qty): void
    {
        $lock = $this->productLockFactory->createLock('product-stock_decrease');
        $lock->acquire(true);

        $this->repository->increaseQty($product, $qty);
        $lock->release();
    }
}
