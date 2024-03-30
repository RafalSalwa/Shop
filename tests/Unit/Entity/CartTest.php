<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use App\Enum\CartStatus;
use App\Enum\SubscriptionTier;
use App\Exception\ItemNotFoundException;
use App\Tests\Helpers\CouponHelperTrait;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use App\ValueObject\CouponCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Cart::class)]
#[UsesClass(className: CartStatus::class)]
#[UsesClass(className: CouponCode::class)]
#[UsesClass(className: CartItem::class)]
#[UsesClass(className: Product::class)]
#[UsesClass(className: SubscriptionPlan::class)]
#[UsesClass(className: SubscriptionTier::class)]
final class CartTest extends TestCase
{
    use CouponHelperTrait;
    use ProductHelperCartItemTrait;
    private Cart $cart;

    protected function setUp(): void
    {
        $cart = new Cart(1);
        $this->setProtectedProperty($cart, 'id', 1);
        $cart->setStatus(CartStatus::CREATED);
        $cart->setUserId(1);
        $this->cart = $cart;
    }

    public function testCreateCart(): void
    {
        $this->assertInstanceOf(Cart::class, $this->cart);
        $this->assertCount(0, $this->cart->getItems());
        $this->assertSame(1, $this->cart->getUserId());
    }

    public function testGetTotalAmount(): void
    {
        $cart = $this->cart;
        $this->assertSame(0, $cart->getTotalAmount());
    }

    public function testJsonSerialize(): void
    {
        $cart = $this->cart;
        $this->assertIsArray($cart->jsonSerialize());
        $this->assertArrayHasKey('status', $cart->jsonSerialize());
        $this->assertArrayHasKey('items', $cart->jsonSerialize());
        $this->assertArrayHasKey('created_at', $cart->jsonSerialize());
    }

    public function testGetUserId(): void
    {
        $this->assertSame(1, $this->cart->getUserId());
    }

    public function testGetStatus(): void
    {
        $this->assertSame(CartStatus::CREATED, $this->cart->getStatus());
    }

    public function testGetCoupon(): void
    {
        $cart = $this->cart;
        $this->assertNull($cart->getCoupon());
        $cart->applyCoupon($this->getHelperCartCoupon());
    }

    public function testApplyCoupon(): void
    {
        $cart = $this->cart;
        $this->assertNull($cart->getCoupon());
        $cart->applyCoupon($this->getHelperCartCoupon());
    }

    public function testGetItems(): void
    {
        $cart = $this->cart;
        $this->assertCount(0, $cart->getItems());
        $cart->applyCoupon($this->getHelperCartCoupon());
        $this->assertInstanceOf(CouponCode::class, $cart->getCoupon());
    }

    public function testRemoveNonExistentItem(): void
    {
        $cart = $this->cart;
        $product = $this->getHelperProductCartItem();
        $cart->addItem($product);
        $this->assertCount(1, $cart->getItems());

        $this->expectException(ItemNotFoundException::class);
        $cart->removeItem($this->getHelperProductCartItem(2));
    }

    public function testRemoveItem(): void
    {
        $cart = $this->cart;
        $product = $this->getHelperProductCartItem();
        $cart->addItem($product);
        $this->assertCount(1, $cart->getItems());
        $cart->removeItem($product);
        $this->assertCount(0, $cart->getItems());

        $this->expectException(ItemNotFoundException::class);
        $cart->removeItem($product);
    }

    public function testSetUserId(): void
    {
        $cart = $this->cart;
        $this->assertSame(1, $cart->getUserId());
    }

    public function testPreUpdate(): void
    {
        $cart = $this->cart;
        $cart->preUpdate();
        $this->assertSame($this->cart, $cart);
    }

    public function testGetTotalItemsCount(): void
    {
        $cart = $this->cart;
        $product = $this->getHelperProductCartItem(id: 1);

        $cart->addItem($product);
        $this->assertCount(1, $cart->getItems());
        $product2 = $this->getHelperProductCartItem(id: 2);
        $cart->addItem($product2);
        $this->assertSame(2, $cart->getTotalItemsCount());
        $product3 = $this->getHelperProductCartItem(id: 3);
        $cart->addItem($product3);
        $this->assertGreaterThanOrEqual(3, $cart->getTotalItemsCount());
        $cart->addItem($this->getHelperProductCartItem(id: 3));
        $this->assertGreaterThanOrEqual(4, $cart->getTotalItemsCount());
    }

    public function testGetItemById(): void
    {
        $cart = $this->cart;
        $this->assertSame(0, $cart->getItems()->count());
        $product = $this->getHelperProductCartItem();
        $cart->addItem($product);
        $this->assertNotNull($cart->getItemById($product->getId()));
    }

    public function testItemExists(): void
    {
        $cart = $this->cart;
        $this->assertCount(0, $cart->getItems());
        $product = $this->getHelperProductCartItem();
        $cart->addItem($product);
        $this->assertCount(1, $cart->getItems());
        $cartItem = $cart->getItemById($product->getId());
        $this->assertTrue($cart->hasItem($cartItem));
    }

    public function testGetItem(): void
    {
        $cart = $this->cart;
        $this->assertSame(0, $cart->getItems()->count());
        $product = $this->getHelperProductCartItem();
        $cart->addItem($product);
        $this->assertNotNull($cart->getItemById($product->getId()));
        $cartItem = $cart->getItemById($product->getId());
        $this->assertInstanceOf(CartItemInterface::class, $cart->getItem($cartItem));
    }

    public function testAddItem(): void
    {
        $cart = $this->cart;
        $product = $this->getHelperProductCartItem(id: 1);

        $cart->addItem($product);
        $this->assertSame(1, $cart->getItems()->count());
        $product2 = $this->getHelperProductCartItem(id: 2);
        $cart->addItem($product2);
        $this->assertSame(2, $cart->getTotalItemsCount());
    }

    public function testGetItemsPrice(): void
    {
        $cart = $this->cart;
        $product = $this->getHelperProductCartItem(id: 1);

        $cart->addItem($product);
        $this->assertSame(1, $cart->getItems()->count());
        $product2 = $this->getHelperProductCartItem(id: 2);
        $cart->addItem($product2);
        $this->assertSame(2, $cart->getTotalItemsCount());
        $this->assertSame(200, $cart->getItemsPrice());
    }

    public function testGetId(): void
    {
        $cart = $this->cart;
        $this->assertSame(1, $cart->getId());
    }

    public function testSetStatus(): void
    {
        $cart = $this->cart;
        $this->assertInstanceOf(CartStatus::class, $cart->getStatus());
        $this->assertSame(CartStatus::CREATED, $cart->getStatus());
    }
}
