<?php

namespace App\Storage;

use App\Entity\Cart;
use App\Repository\CartRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CartSessionStorage
{
    public const CART_KEY_NAME = 'cart_id';
    public const ADDR_KEY_NAME = 'addr_id';

    public function __construct(
        private readonly RequestStack   $requestStack,
        private readonly CartRepository $cartRepository,
        private readonly Security       $security
    )
    {
    }

    /**
     * Gets the cart in session.
     */
    public function getCart($sorted = false): ?Cart
    {
        $cart = $this->cartRepository->findOneBy([
            'user' => $this->getUser(),
            'status' => Cart::STATUS_CREATED,
        ], ['createdAt' => 'DESC']);

        return $cart;
    }

    private function getUser(): ?UserInterface
    {
        return $this->security->getUser();
    }

    /**
     * Sets the cart in session.
     */
    public function setCart(Cart $cart): void
    {
        $this->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function removeCart(): void
    {
        $this->getSession()->remove(self::CART_KEY_NAME);
    }

    public function setDeliveryAddressId(int $addId)
    {
        $this->getSession()->set(self::ADDR_KEY_NAME, $addId);
    }

    public function getDeliveryAddressId()
    {
        return $this->getSession()->get(self::ADDR_KEY_NAME);
    }

    /**
     * Returns the cart id.
     */
    private function getCartId(): ?int
    {
        $cartId = $this->getSession()->get(self::CART_KEY_NAME);
        if (!$cartId) {
            return null;
        }

        return (int)$this->getSession()->get(self::CART_KEY_NAME);
    }
}
