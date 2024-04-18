<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Entity\AbstractCartItem;
use App\Entity\Cart;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Product;
use App\Entity\ProductCartItem;
use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;
use App\Exception\CartOperationException;
use App\Exception\ItemNotFoundException;
use App\Factory\CartItemFactory;
use App\Repository\CartItemRepository;
use App\Service\CartItemService;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(className: CartItemService::class)]
#[UsesClass(className: CartItemRepository::class)]
#[UsesClass(className: CartItemFactory::class)]
#[UsesClass(className: AbstractCartItem::class)]
#[UsesClass(className: Cart::class)]
#[UsesClass(className: Product::class)]
#[UsesClass(className: ProductCartItem::class)]
#[UsesClass(className: SubscriptionPlan::class)]
#[UsesClass(className: SubscriptionTier::class)]
final class CartItemServiceTest extends KernelTestCase
{
    use ProductHelperCartItemTrait;

    private CartItemFactory $cartItemFactory;

    private CartItemRepository $cartItemRepository;

    private CartItemService $cartItemService;

    private ProductCartItem $cartItem;

    protected function setUp(): void
    {
        // Mock dependencies using PHPUnit's built-in mocks (or use a mocking library like Mockery)
        $this->cartItemFactory = $this->createMock(CartItemFactory::class);
        $this->cartItemRepository = $this->createMock(CartItemRepository::class);

        $this->cartItemService = new CartItemService($this->cartItemFactory, $this->cartItemRepository);
        $this->cartItem = $this->getHelperProductCartItem(1);
    }

    public function testCreateCartItem(): void
    {
        $cart = new Cart(1);
        $itemId = 123;
        $quantity = 2;

        // Expect the cartItemFactory to be called with specific arguments
        $createdCartItem = $this->createMock(CartItemInterface::class);

        // Call the create method of CartItemService and assert the returned value
        $result = $this->cartItemService->create($cart, $itemId, $quantity);
        $this->assertSame($createdCartItem, $result);
    }

    public function testGetItem(): void
    {
        $itemId = 123;
        $foundCartItem = $this->createMock(CartItemInterface::class);

        // Expect the cartItemRepository to return a specific cart item when find method is called

        // Call the getItem method of CartItemService and assert the returned value
        $result = $this->cartItemService->getItem($itemId);
        $this->assertSame($foundCartItem, $result);
    }

    public function testGetItemThrowsCartOperationException(): void
    {
        $itemId = 123;
        // Mock the repository to return null (item not found)

        // Assert that getItem method throws CartOperationException
        $this->expectException(CartOperationException::class);
        $this->cartItemService->getItem($itemId);
    }

    public function testRemoveItem(): void
    {
        $cart = new Cart(1);
        $cart->addItem($this->cartItem);

        $this->assertTrue($cart->hasItem($this->cartItem));

        $this->cartItemService->removeItem($this->cartItem);
        $this->assertFalse($cart->hasItem($this->cartItem));
    }

    public function testRemoveItemThrowsItemNotFoundException(): void
    {
        $cartItem = $this->createMock(CartItemInterface::class);

        // Mock the cart to not have the specified cart item
        $cart = new Cart(1);

        // Assert that cartItem is not in the cart
        $this->assertFalse($cart->hasItem($cartItem));

        // Assert that removeItem method throws ItemNotFoundException
        $this->expectException(ItemNotFoundException::class);
        $this->cartItemService->removeItem($cartItem);
    }
}
