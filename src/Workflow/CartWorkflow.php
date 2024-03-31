<?php

declare(strict_types=1);

namespace App\Workflow;

use App\Entity\Contracts\StockManageableInterface;
use App\Enum\StockOperation;
use App\Exception\CartOperationException;
use App\Exception\Contracts\CartOperationExceptionInterface;
use App\Exception\Contracts\StockOperationExceptionInterface;
use App\Exception\InsufficientStockException;
use App\Exception\InvalidCouponCodeException;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Exception\StockOperationException;
use App\Service\CartItemService;
use App\Service\CartService;
use App\Service\CouponService;
use App\Service\ProductStockService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use function assert;
use function sprintf;

final readonly class CartWorkflow
{
    public function __construct(
        private CartItemService $cartItemService,
        private CartService $cartService,
        private LockFactory $cartLockFactory,
        private ProductStockService $stockService,
        private CouponService $couponService,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws CartOperationExceptionInterface
     * @throws StockOperationExceptionInterface
     */
    public function add(int $prodId, int $quantity): void
    {
        try {
            $lock = $this->cartLockFactory->createLock(sprintf('cart_item_add_%d', $prodId));
            $lock->acquire(true);

            $this->stockService->checkStockIsAvailable($prodId, $quantity);
            $cart = $this->cartService->getCurrentCart();
            $cartItem = $this->cartItemService->create($cart, $prodId, $quantity);
            $this->cartService->add($cartItem);

            $entity = $cartItem->getReferencedEntity();
            assert($entity instanceof StockManageableInterface);
            $this->stockService->changeStock($entity, StockOperation::Decrease, $quantity);

            $lock->release();
        } catch (AccessDeniedException | ItemNotFoundException  $exception) {
            $this->logger->error($exception->getMessage());

            throw new CartOperationException(message: $exception->getMessage(), previous: $exception);
        } catch (InsufficientStockException | ProductStockDepletedException $exception) {
            $this->logger->error(
                message: $exception->getMessage(),
                context: ['operation' => StockOperation::decrease(), 'item_id' => $prodId, 'quantity' => $quantity],
            );

            throw new StockOperationException(message: $exception->getMessage(), previous: $exception);
        }
    }

    public function updateItem(int $itemId, int $quantity): void
    {
        try {
            $lock = $this->cartLockFactory->createLock(sprintf('cart_item_update_%d', $itemId));
            $lock->acquire(true);

            $this->cartService->updateQuantity($itemId, $quantity);

            $lock->release();
        } catch (
            CartOperationExceptionInterface | ItemNotFoundException | StockOperationExceptionInterface $exception
        ) {
            $this->logger->error(
                message: $exception->getMessage(),
                context: ['operation' => 'update Item qty', 'item_id' => $itemId, 'quantity' => $quantity],
            );
        }
    }

    /**
     * @throws CartOperationExceptionInterface
     * @throws StockOperationExceptionInterface
     */
    public function remove(int $itemId): void
    {
        try {
            $lock = $this->cartLockFactory->createLock(sprintf('cart_item_update_%d', $itemId));
            $lock->acquire(true);

            $item = $this->cartItemService->getItem($itemId);
            $this->cartService->removeItem($item);

            $this->stockService->restoreStock($item);
            $this->cartService->save($this->cartService->getCurrentCart());

            $lock->release();
        } catch (ItemNotFoundException $itemNotFoundException) {
            $this->logger->error($itemNotFoundException->getMessage());

            throw new CartOperationException(
                message: $itemNotFoundException->getMessage(),
                previous: $itemNotFoundException,
            );
        }
    }

    /** @throws CartOperationException */
    public function applyCouponCode(string $couponCode): void
    {
        try {
            $coupon = $this->couponService->getCouponType($couponCode);
            $this->cartService->applyCoupon($coupon);
            $this->cartService->save($this->cartService->getCurrentCart());
        } catch (InvalidCouponCodeException $invalidCouponCodeException) {
            $this->logger->error($invalidCouponCodeException->getMessage());

            throw new CartOperationException(
                message: $invalidCouponCodeException->getMessage(),
                previous: $invalidCouponCodeException,
            );
        }
    }
}
