<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AbstractCartItem;
use App\Entity\Cart;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Contracts\StockManageableInterface;
use App\Entity\SubscriptionPlanCartItem;
use App\Enum\CartStatus;
use App\Enum\StockOperation;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Exception\TooManySubscriptionsException;
use App\Factory\CartFactory;
use App\Factory\CartItemFactory;
use App\Storage\CartSessionStorage;
use App\ValueObject\CouponCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Lock\LockFactory;
use function is_subclass_of;

final readonly class CartService
{
    public function __construct(
        private CartSessionStorage $cartSessionStorage,
        private CartFactory $cartFactory,
        private EntityManagerInterface $entityManager,
        private CartItemFactory $cartItemFactory,
        private ProductStockService $stockService,
        private LockFactory $cartLockFactory,
    ) {
    }

    public function clearCart(): void
    {
        $cart = $this->getCurrentCart();
        foreach ($cart->getItems() as $item) {
            $cart->removeItem($item);
            $this->stockService->restoreStock($item);
            $this->save($cart);
        }

        $cart->getItems()->clear();
        $this->cartSessionStorage->removeCart();
    }

    public function getCurrentCart(): Cart
    {
        $cart = $this->cartSessionStorage->getCart();
        if (! $cart instanceof Cart) {
            $cart = $this->cartFactory->create();
        }

        return $cart;
    }

    /** @throws ItemNotFoundException */
    public function removeItem(AbstractCartItem $cartItem): void
    {
        $cart = $this->getCurrentCart();
        if (false === $cart->itemExists($cartItem)) {
            throw new ItemNotFoundException('Item already removed');
        }

        $cart->removeItem($cartItem);
    }

    /**
     * Persists the cart in database and session.
     */
    public function save(?Cart $cart = null): void
    {
        if (! $cart instanceof Cart) {
            $cart = $this->getCurrentCart();
        }
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        $this->cartSessionStorage->setCart($cart);
    }

    public function confirmCart(): void
    {
        $cart = $this->getCurrentCart();
        $cart->setStatus(CartStatus::CONFIRMED);

        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function useDefaultDeliveryAddress(int $deliveryAddressId): void
    {
        $this->cartSessionStorage->setDeliveryAddressId($deliveryAddressId);
    }

    public function getDefaultDeliveryAddressId(): ?int
    {
        return $this->cartSessionStorage->getDeliveryAddressId();
    }

    /** @throws ProductStockDepletedException|ItemNotFoundException */
    public function add(CartItemInterface $cartItem): void
    {
        $lock = $this->cartLockFactory->createLock('cart_item_add');
        $lock->acquire(true);

        $this->stockService->checkStockIsAvailable($cartItem);
        $this->getCurrentCart()->addItem($cartItem);

        if (true === is_subclass_of($cartItem, StockManageableInterface::class)) {
            $this->stockService->changeStock($cartItem, StockOperation::Decrease, $cartItem->getQuantity());
        }

        $lock->release();
    }

    public function checkSubscriptionsCount(CartItemInterface $cartItem): void
    {
        $cart = $this->getCurrentCart();

        if ($cartItem instanceof SubscriptionPlanCartItem && $cart->itemExists($cartItem)) {
            throw new TooManySubscriptionsException('You can have only one subscription in cart');
        }
    }

    public function applyCoupon(CouponCode $coupon): void
    {
        $cart = $this->getCurrentCart();
        $cart->applyCoupon($coupon);
    }
}
