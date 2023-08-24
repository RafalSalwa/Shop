<?php

namespace App\Service;

use App\Entity\CartItem;
use App\Entity\CartItemInterface;
use App\Entity\Product;
use App\Event\StockDepletedEvent;
use App\Exception\ProductNotFound;
use App\Exception\ProductStockDepleted;
use App\Repository\ProductRepository;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProductStockService
{
    private LockFactory $productLockFactory;
    private ProductRepository $repository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(LockFactory $productLockFactory, ProductRepository $repository, EventDispatcherInterface $eventDispatcher)
    {
        $this->productLockFactory = $productLockFactory;
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws ProductStockDepleted
     */
    public function checkStockIsAvailable(CartItemInterface $entity): void
    {
        if ($entity instanceof Product) {
            $product = $this->repository->find($entity->getId());
            if (!$product) {
                throw new ProductNotFound(sprintf("Product #%d not found.", $entity->getId()));
            }
            if ($product->getUnitsInStock() == 0) {
                throw new ProductStockDepleted("For this product stock is depleted.");
            }
        }
    }

    public function restoreStock(CartItem $item, string $STOCK_INCREASE): void
    {
        $this->changeStock($item->getDestinationEntity(), Product::STOCK_INCREASE, $item->getQuantity());
    }

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
        if ($product->getUnitsInStock() == 1) {
            $event = new StockDepletedEvent($product);
            $this->eventDispatcher->dispatch($event);
        }
        $this->repository->decreaseQty($product);

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
