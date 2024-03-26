<?php

declare(strict_types=1);

namespace App\Storage;

use App\Entity\Cart;
use App\Entity\Contracts\ShopUserInterface;
use App\Repository\CartRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

use function assert;
use function is_a;
use function is_subclass_of;

final readonly class CartSessionStorage
{
    public const CART_KEY_NAME = 'cart_id';

    public const ADDR_KEY_NAME = 'addr_id';

    public function __construct(
        private RequestStack $requestStack,
        private CartRepository $cartRepository,
        private Security $security,
        private ParameterBagInterface $parameterBag,
    ) {}

    /**
     * Gets the cart in session.
     */
    public function getCart(): ?Cart
    {
        return $this->cartRepository->findOneBy(
            [
                'userId' => $this->getUser()->getToken()->getSub(),
                'status' => Cart::STATUS_CREATED,
            ],
            ['createdAt' => 'DESC'],
        );
    }

    private function getUser(): ShopUserInterface
    {
        $user = $this->security->getUser();
        assert(is_subclass_of($user, ShopUserInterface::class));

        return $user;
    }

    public function setCart(Cart $cart): void
    {
        $request = $this->requestStack->getCurrentRequest();
        assert(is_a($request, Request::class));
        if (true === $this->security->getFirewallConfig($request)?->isStateless()) {
            return;
        }

        $this->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }

    private function getSession(): SessionInterface
    {
        // until this https://github.com/symfony/symfony/discussions/45662 won't be fixed
        // that is the easiest solution for session storage between redis and filesystem
        if ('test' === $this->parameterBag->get('kernel.environment')) {
            $sessionSavePath = $this->parameterBag->get('session.save_path');

            $mockFileSessionStorage = new MockFileSessionStorage($sessionSavePath);
            $session = new Session($mockFileSessionStorage);

            $session->start();
            $session->save();

            return $session;
        }

        return $this->requestStack->getSession();
    }

    public function removeCart(): void
    {
        $this->getSession()->remove(self::CART_KEY_NAME);
    }
}
