<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Contracts\CartItemInterface;
use App\Entity\Contracts\StockManageableInterface;
use App\Enum\StockOperation;
use App\Event\StockDepletedEvent;
use App\Exception\ProductStockDepletedException;
use App\Repository\ProductRepository;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use function is_subclass_of;

final readonly class ProductStockService
{
    public function __construct(
        private LockFactory $productLockFactory,
        private ProductRepository $productRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    /** @throws ProductStockDepletedException */
    public function checkStockIsAvailable(CartItemInterface $cartItem): void
    {
        $referencedEntity = $cartItem->getReferencedEntity();
        if (false === is_subclass_of($referencedEntity, StockManageableInterface::class)) {
            return;
        }
        if (0 === $referencedEntity->getUnitsInStock()) {
            throw new ProductStockDepletedException('For this product stock is depleted.');
        }
    }

    /** @throws ProductStockDepletedException */
    public function restoreStock(CartItemInterface $cartItem): void
    {
        $referencedEntity = $cartItem->getReferencedEntity();
        if (false === is_subclass_of($referencedEntity, StockManageableInterface::class)) {
            return;
        }

        $this->changeStock($referencedEntity, StockOperation::Increase, $cartItem->getQuantity());
    }

    /** @throws ProductStockDepletedException */
    public function changeStock(StockManageableInterface $entity, StockOperation $operation, int $quantity): void
    {
        match ($operation) {
            StockOperation::Decrease => $this->decrease($entity, $quantity),
            StockOperation::Increase => $this->increase($entity, $quantity),
        };
    }

    /** @throws ProductStockDepletedException */
    private function decrease(StockManageableInterface $entity, int $qty): void
    {
        $sharedLock = $this->productLockFactory->createLock('product-stock_decrease');
        $sharedLock->acquire(true);

        if (0 === $entity->getUnitsInStock()) {
            throw new ProductStockDepletedException();
        }

        if (1 === $entity->getUnitsInStock()) {
            $stockDepletedEvent = new StockDepletedEvent($entity);
            $this->eventDispatcher->dispatch($stockDepletedEvent);
        }
        $newQty = $entity->getUnitsInStock() - $qty;
        $this->productRepository->updateStock($entity, $newQty);

        $sharedLock->release();
    }

    private function increase(StockManageableInterface $entity, int $qty): void
    {
        $sharedLock = $this->productLockFactory->createLock('product-stock_increase');
        $sharedLock->acquire(true);

        $newQty = $entity->getUnitsInStock() - $qty;
        $this->productRepository->updateStock($entity, $newQty);

        $sharedLock->release();
    }
}
