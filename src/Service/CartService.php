<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Contracts\ShopUserInterface;
use App\Exception\CartOperationException;
use App\Exception\Contracts\CartOperationExceptionInterface;
use App\Exception\Contracts\StockOperationExceptionInterface;
use App\Exception\ItemNotFoundException;
use App\Exception\ProductStockDepletedException;
use App\Storage\Cart\Contracts\CartStorageInterface;
use App\ValueObject\CouponCode;
use Symfony\Bundle\SecurityBundle\Security;

use function assert;
use function sprintf;

/**
 * @see \App\Tests\Service\CartServiceTest
 */
final readonly class CartService
{
    public function __construct(
        private CartStorageInterface $cartStorage,
        private CartItemService $cartItemService,
        private ProductStockService $stockService,
        private Security $security,
        private int $cartItemMaxCapacity,
    ) {}

    public function getCurrentCart(): Cart
    {
        $user = $this->security->getUser();
        assert($user instanceof ShopUserInterface);

        return $this->cartStorage->getCurrentCart($user->getId());
    }

    /**
     * @throws ItemNotFoundException
     * @throws ProductStockDepletedException
     */
    public function clearCart(): void
    {
        $cart = $this->getCurrentCart();
        $this->cartStorage->purge($cart);
    }

    /**
     * Persists the cart in database and session.
     */
    public function save(Cart $cart): void
    {
        $this->cartStorage->save($cart);
    }

    public function confirmCart(): void
    {
        $cart = $this->getCurrentCart();
        $this->cartStorage->confirm($cart);
    }

    public function applyCoupon(CouponCode $coupon): void
    {
        $cart = $this->getCurrentCart();
        $cart->applyCoupon($coupon);
    }

    /**
     * @throws CartOperationExceptionInterface
     * @throws ItemNotFoundException
     * @throws StockOperationExceptionInterface
     */
    public function updateQuantity(int $itemId, int $quantity): void
    {
        if ($quantity > $this->cartItemMaxCapacity) {
            throw new CartOperationException(
                message: sprintf(
                    'maximum number of Items (%s) per product has been exceeded',
                    $this->cartItemMaxCapacity,
                ),
            );
        }

        $cart = $this->getCurrentCart();
        $item = $cart->getItemById($itemId);
        $this->stockService->checkStockIsAvailable($item->getReferencedEntity()->getId(), $quantity);

        $this->cartItemService->removeItem($item);
        $item->updateQuantity($quantity);
        $this->add($item);
    }

    /** @throws ItemNotFoundException */
    public function add(CartItemInterface $cartItem): void
    {
        $cart = $this->getCurrentCart();
        $cart->addItem($cartItem);
        $this->save($cart);
    }

    /** @throws ItemNotFoundException */
    public function removeItem(CartItemInterface $cartItem): void
    {
        $cart = $this->getCurrentCart();
        if (false === $cart->hasItem($cartItem)) {
            throw new ItemNotFoundException('Item already removed');
        }

        $cart->removeItem($cartItem);
    }
}
