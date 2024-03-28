<?php

declare(strict_types=1);

namespace App\Workflow;

use App\Entity\Contracts\CartItemInterface;
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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use function assert;

final readonly class CartWorkflow
{
    public function __construct(
        private CartItemService $cartItemService,
        private CartService $cartService,
        private ProductStockService $productStockService,
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
            $cartItem = $this->cartItemService->create($prodId, $quantity);
            $this->cartService->add($cartItem);
            $entity = $cartItem->getReferencedEntity();
            assert($entity instanceof StockManageableInterface);

            $this->productStockService->changeStock($entity, StockOperation::Decrease, $quantity);
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

    /**
     * @throws CartOperationExceptionInterface
     * @throws StockOperationExceptionInterface
     */
    public function remove(CartItemInterface $cartItem): void
    {
        try {
            $this->cartService->removeItem($cartItem);
            $this->productStockService->restoreStock($cartItem);
            $this->cartService->save($this->cartService->getCurrentCart());
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
