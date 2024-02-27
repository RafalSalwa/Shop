<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\CartItemInterface;
use App\Entity\Product;
use App\Entity\ProductCartItem;
use App\Entity\SubscriptionPlanCartItem;
use App\Exception\ItemNotFoundException;
use App\Exception\TooManySubscriptionsException;
use App\Factory\CartFactory;
use App\Factory\CartItemFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Throwable;
use function dd;

final readonly class CartService
{
    public function __construct(
        private CartSessionStorage $cartSessionStorage,
        private CartFactory $cartFactory,
        private EntityManagerInterface $entityManager,
        private CartItemFactory $cartItemFactory,
        private ProductStockService $productStockService,
        private LockFactory $cartLockFactory,
        private LoggerInterface $logger,
    ) {
    }

    public function clearCart(): void
    {
        $cart = $this->getCurrentCart();
        foreach ($cart->getItems() as $item) {
            $cart->removeItem($item);
            $this->productStockService->restoreStock($item);
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
        $cart->setStatus(Cart::STATUS_CONFIRMED);

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

    public function addProduct(Product $product): void
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->makeCartItem($product);
        $this->productStockService->changeStock($product, Product::STOCK_DECREASE, 1);

        $cart->addItem($cartItem);

        $this->save($cart);
    }

    public function makeCartItem(object $entity): CartItem|ProductCartItem|SubscriptionPlanCartItem
    {
        return $this->cartItemFactory->create($entity);
    }

    public function add(CartItemInterface $cartItem, int $quantity): void
    {
        try {
            $lock = $this->cartLockFactory->createLock('cart_item_add');
            $lock->acquire(true);

            $this->productStockService->checkStockIsAvailable($cartItem);

            $this->checkSubscriptionsCount($cartItem);

            $cart = $this->getCurrentCart();
            $cart->addItem($cartItem);
            $this->save($cart);

            $this->productStockService->changeStock($cartItem, Product::STOCK_DECREASE, $quantity);

            $lock->release();
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        } catch (ItemNotFoundException|AccessDeniedException) {
        } catch (Throwable $e) {
            dd($e::class, $e->getMessage());
        }
    }

    public function checkSubscriptionsCount(CartItemInterface $cartItem): void
    {
        $cart = $this->getCurrentCart();

        if ($cartItem instanceof SubscriptionPlanCartItem && $cart->itemTypeExists($cartItem)) {
            throw new TooManySubscriptionsException('You can have only one subscription in cart');
        }
    }

    public function removeItemIfExists(CartItem $cartItem): void
    {
        $cart = $this->getCurrentCart();

        if (! $cart->getItems()->contains($cartItem)) {
            return;
        }

        $cart->getItems()
            ->removeElement($cartItem);
    }
}
