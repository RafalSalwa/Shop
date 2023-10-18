<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CartItemInterface;
use App\Entity\Product;
use App\Entity\StockManageableInterface;
use App\Event\StockDepletedEvent;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Repository\ProductRepository;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProductStockService
{
    public function __construct(
        private readonly LockFactory $productLockFactory,
        private readonly ProductRepository $repository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws ProductStockDepletedException
     * @throws ItemNotFoundException
     */
    public function checkStockIsAvailable(CartItemInterface $entity): void
    {
        $product = $entity->getReferenceEntity();
        if ($product instanceof StockManageableInterface && 0 === $product->getUnitsInStock()) {
            throw new ProductStockDepletedException('For this product stock is depleted.');
        }
    }

    /**
     * @throws ProductStockDepletedException
     */
    public function restoreStock(CartItemInterface $item): void
    {
        $this->changeStock($item, Product::STOCK_INCREASE, $item->getQuantity());
    }

    /**
     * @throws ProductStockDepletedException
     */
    public function changeStock(CartItemInterface $entity, string $operation, int $qty): void
    {
        $product = $entity->getReferencedEntity();
        if ($product instanceof StockManageableInterface) {
            match ($operation) {
                Product::STOCK_DECREASE => $this->decrease($product, $qty),
                Product::STOCK_INCREASE => $this->increase($product, $qty)
            };
        }
    }

    /**
     * @throws ProductStockDepletedException
     */
    private function decrease(StockManageableInterface $product, int $qty): void
    {
        $lock = $this->productLockFactory->createLock('product-stock_decrease');
        $lock->acquire(true);

        if (0 === $product->getUnitsInStock()) {
            throw new ProductStockDepletedException();
        }

        if (1 === $product->getUnitsInStock()) {
            $event = new StockDepletedEvent($product);
            $this->eventDispatcher->dispatch($event);
        }
        $this->repository->decreaseQty($product, $qty);

        $lock->release();
    }

    private function increase(StockManageableInterface $product, int $qty): void
    {
        $lock = $this->productLockFactory->createLock('product-stock_decrease');
        $lock->acquire(true);

        $this->repository->increaseQty($product, $qty);
        $lock->release();
    }
}
