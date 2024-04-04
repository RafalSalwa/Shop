<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\Entity\AbstractCartItem;
use App\Entity\Cart;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Product;
use App\Entity\ProductCartItem;
use App\Entity\SubscriptionPlan;
use App\Enum\CartOperationEnum;
use App\Enum\SubscriptionTier;
use App\Exception\InsufficientStockException;
use App\Exception\ItemNotFoundException;
use App\Factory\CartItemFactory;
use App\Service\ProductsService;
use App\Tests\Helpers\CartHelperTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[CoversClass(className: CartItemFactory::class)]
#[CoversClass(className: InsufficientStockException::class)]
#[CoversClass(className: ItemNotFoundException::class)]
#[UsesClass(className: AbstractCartItem::class)]
#[UsesClass(className: Cart::class)]
#[UsesClass(className: Product::class)]
#[UsesClass(className: ProductCartItem::class)]
#[UsesClass(className: SubscriptionPlan::class)]
#[UsesClass(className: CartOperationEnum::class)]
#[UsesClass(className: SubscriptionTier::class)]
final class CartItemFactoryTest extends TestCase
{
    use CartHelperTrait;

    private CartItemFactory $cartItemFactory;

    private MockObject $productsService;

    private MockObject $authorizationChecker;

    private Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cart = $this->getHelperCart(id: 1);
        // Mocking dependencies
        $this->productsService = $this->createMock(ProductsService::class);
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        // Creating an instance of CartItemFactory with mocked dependencies
        $this->cartItemFactory = new CartItemFactory($this->productsService, $this->authorizationChecker);
    }

    public function testCreateCartItem(): void
    {
        $cart = $this->cart;
        $itemId = 1;
        $quantity = 2;

        // Mocking product retrieval from service
        $product = new Product('Test Product', '1 item', 10_00, 5, 3);
        $this->productsService->expects($this->once())
            ->method('byId')
            ->with($itemId)
            ->willReturn($product);

        // Mocking authorization check
        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with(CartOperationEnum::addToCart(), $product)
            ->willReturn(true);

        // Creating the cart item
        $createdCartItem = $this->cartItemFactory->create($cart, $itemId, $quantity);

        // Assertions
        $this->assertInstanceOf(CartItemInterface::class, $createdCartItem);
        $this->assertInstanceOf(ProductCartItem::class, $createdCartItem);
        $this->assertSame($cart, $createdCartItem->getCart());
        $this->assertSame($product, $createdCartItem->getReferencedEntity());
        $this->assertSame($quantity, $createdCartItem->getQuantity());
    }

    public function testCreateCartItemWithInsufficientStock(): void
    {
        $this->expectException(InsufficientStockException::class);

        $cart = $this->cart;
        $itemId = 1;
        $quantity = 10;

        // Mocking product retrieval from service
        $product = new Product('Test Product', '1 item', 10_00, 5, 3);
        $this->productsService->expects($this->once())
            ->method('byId')
            ->with($itemId)
            ->willReturn($product);

        // Creating the cart item should throw an InsufficientStockException
        $this->cartItemFactory->create($cart, $itemId, $quantity);
    }

    public function testCreateCartItemWithItemNotFound(): void
    {
        $this->expectException(ItemNotFoundException::class);

        $cart = $this->cart;
        $itemId = 1;
        $quantity = 2;

        // Mocking product retrieval from service
        $this->productsService->expects($this->once())
            ->method('byId')
            ->with($itemId)
            ->willReturn(null);

        // Creating the cart item should throw an ItemNotFoundException
        $this->cartItemFactory->create($cart, $itemId, $quantity);
    }

    public function testCreateCartItemWithAccessDenied(): void
    {
        $this->expectException(AccessDeniedException::class);

        $cart = $this->cart;
        $itemId = 1;
        $quantity = 2;

        // Mocking product retrieval from service
        $product = new Product('Test Product', '1 item', 10_00, 5, 3);
        $this->productsService->expects($this->once())
            ->method('byId')
            ->with($itemId)
            ->willReturn($product);

        // Mocking authorization check
        $this->authorizationChecker->expects($this->once())
            ->method('isGranted')
            ->with(CartOperationEnum::addToCart(), $product)
            ->willReturn(false);

        // Creating the cart item should throw an AccessDeniedException
        $this->cartItemFactory->create($cart, $itemId, $quantity);
    }
}
