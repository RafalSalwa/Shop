<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Contracts\CartItemInterface;
use App\Entity\Contracts\StockManageableInterface;
use App\Enum\StockOperation;
use App\Event\StockDepletedEvent;
use App\Exception\Contracts\StockOperationExceptionInterface;
use App\Exception\InsufficientStockException;
use App\Exception\ProductStockDepletedException;
use App\Repository\ProductRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use UnhandledMatchError;

use function is_subclass_of;

final readonly class ProductStockService
{
    public function __construct(
        private LockFactory $productLockFactory,
        private ProductRepository $productRepository,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger,
    ) {}

    /** @throws StockOperationExceptionInterface */
    public function checkStockIsAvailable(int $prodId, int $quantity): void
    {
        $product = $this->productRepository->find($prodId);
        if (null === $product) {
            return;
        }

        if (false === is_subclass_of($product, StockManageableInterface::class)) {
            return;
        }

        if (0 === $product->getUnitsInStock()) {
            throw new ProductStockDepletedException('For this product stock is depleted.');
        }

        if ($quantity >= $product->getUnitsInStock()) {
            throw new InsufficientStockException('There is no available stock units for this product.');
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
        try {
            match ($operation) {
                StockOperation::Decrease => $this->decrease($entity, $quantity),
                StockOperation::Increase => $this->increase($entity, $quantity),
            };
        } catch (UnhandledMatchError $unhandledMatchError) {
            $this->logger->error(
                'Unhandled stock operation ' . $operation->value,
                [$unhandledMatchError->getMessage()],
            );
        }
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
            $this->eventDispatcher->dispatch(new StockDepletedEvent($entity));
        }

        $newQty = $entity->getUnitsInStock() - $qty;
        $this->productRepository->updateStock($entity, $newQty);

        $sharedLock->release();
    }

    private function increase(StockManageableInterface $entity, int $qty): void
    {
        $sharedLock = $this->productLockFactory->createLock('product-stock_increase');
        $sharedLock->acquire(true);

        $newQty = $entity->getUnitsInStock() + $qty;
        $this->productRepository->updateStock($entity, $newQty);

        $sharedLock->release();
    }
}
