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

readonly class ProductStockService
{
    public function __construct(
        private LockFactory $productLockFactory,
        private ProductRepository $repository,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws ProductStockDepletedException
     */
    public function checkStockIsAvailable(CartItemInterface $entity): void
    {
        $product = $entity->getReferenceEntity();
        if ($product instanceof StockManageableInterface && $product->getUnitsInStock() === 0) {
            throw new ProductStockDepletedException('For this product stock is depleted.');
        }
    }

    public function restoreStock(CartItemInterface $item): void
    {
        $this->changeStock($item, Product::STOCK_INCREASE, $item->getQuantity());
    }

    /** @psalm-param Product::STOCK_* $operation */
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

        if ($product->getUnitsInStock() === 0) {
            throw new ProductStockDepletedException();
        }

        if ($product->getUnitsInStock() === 1) {
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
