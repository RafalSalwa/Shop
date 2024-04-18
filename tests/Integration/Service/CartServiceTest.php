<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Entity\Cart;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Contracts\ShopUserInterface;
use App\Model\User;
use App\Service\CartService;
use App\Storage\Cart\Contracts\CartStorageInterface;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use App\Tests\Helpers\TokenTestHelperTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(className: CartService::class)]
#[UsesClass(className: Cart::class)]
final class CartServiceTest extends WebTestCase
{
    use ProductHelperCartItemTrait;
    use TokenTestHelperTrait;

    private CartStorageInterface $cartStorage;

    private CartService $cartService;

    private CartItemInterface $cartItem;

    private ShopUserInterface $shopUser;

    protected function setUp(): void
    {
        $client = self::createClient();
        $user = new User('test@test.com');
        $user->setToken($this->getToken());

        $client->loginUser($user);
        $this->cartService = self::getContainer()->get(CartService::class);
        $this->cartStorage = self::getContainer()->get(CartStorageInterface::class);
        $this->product = $this->getHelperProduct(1);
    }

    public function testAddCartItem(): void
    {
        $cart = new Cart(1);

        $this->cartStorage->save($cart);

        $this->cartService->add($this->cartItem);

        $updatedCart = $this->cartStorage->getCurrentCart($cart->getId());

        $this->assertTrue($updatedCart->hasItem($this->cartItem));
    }

    public function testRemoveItem(): void
    {
        $cart = new Cart(1);
        $cart->addItem($this->cartItem);

        // Save the Cart object using the CartStorageInterface
        $this->cartStorage->save($cart);

        // Call the removeItem method of CartService
        $this->cartService->removeItem($this->cartItem);

        // Fetch the updated Cart object from the storage
        $updatedCart = $this->cartStorage->getCurrentCart($cart->getId());

        // Assert that the Cart object no longer contains the removed cart item
        $this->assertFalse($updatedCart->hasItem($this->cartItem));
    }
}
