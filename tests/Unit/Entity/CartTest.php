<?php

namespace App\Tests\Unit\Entity;

use App\Entity\AbstractCartItem;
use App\Entity\Cart;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Product;
use App\Enum\CartStatus;
use App\Exception\ItemNotFoundException;
use App\Tests\Helpers\CouponHelperTrait;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use App\Tests\Helpers\ProtectedPropertyHelper;
use App\ValueObject\CouponCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Cart::class)]
#[UsesClass(className: CartStatus::class)]
#[UsesClass(className: CouponCode::class)]
#[UsesClass(className: AbstractCartItem::class)]
#[UsesClass(className: Product::class)]
class CartTest extends TestCase
{
    private Cart $cart;
    use CouponHelperTrait;
    use ProductHelperCartItemTrait;
    use ProtectedPropertyHelper;
    
    protected function setUp(): void
    {
        $cart = new Cart();
        $this->setProtectedProperty($cart, 'id', 1);
        $cart->setStatus(CartStatus::CREATED);
        $cart->setUserId(1);
        $this->cart = $cart;
    }

    public function testCreateCart()
    {
        $this->assertInstanceOf(Cart::class, $this->cart);
        $this->assertEquals(0, $this->cart->getItems()->count());
        $this->assertEquals(1, $this->cart->getUserId());
    }

    public function testGetTotalAmount()
    {
        $cart = $this->cart;
        $this->assertEquals(0, $cart->getTotalAmount());
    }

    public function testJsonSerialize()
    {
        $cart = $this->cart;
        $this->assertIsArray($cart->jsonSerialize());
        $this->assertArrayHasKey('status', $cart->jsonSerialize());
        $this->assertArrayHasKey('items', $cart->jsonSerialize());
        $this->assertArrayHasKey('created_at', $cart->jsonSerialize());
    }

    public function testGetUserId()
    {
        $this->assertEquals(1, $this->cart->getUserId());
    }

    public function testGetStatus()
    {
        $this->assertEquals(CartStatus::CREATED, $this->cart->getStatus());
    }

    public function testGetCoupon()
    {
        $cart = $this->cart;
        $this->assertNull($cart->getCoupon());
        $cart->applyCoupon($this->getHelperCartCoupon());
        $this->assertInstanceOf(CouponCode::class, $cart->getCoupon());
    }

    public function testApplyCoupon()
    {
        $cart = $this->cart;
        $this->assertNull($cart->getCoupon());
        $cart->applyCoupon($this->getHelperCartCoupon());
        $this->assertInstanceOf(CouponCode::class, $cart->getCoupon());
    }

    public function testGetItems()
    {
        $cart = $this->cart;
        $this->assertEquals(0, $cart->getItems()->count());
        $cart->applyCoupon($this->getHelperCartCoupon());
        $this->assertInstanceOf(CouponCode::class, $cart->getCoupon());
    }

    public function testRemoveNonExistentItem()
    {
        $cart = $this->cart;
        $product = $this->getHelperProductCartItem();
        $cart->addItem($product);
        $this->assertEquals(1, $cart->getItems()->count());

        $this->expectException(ItemNotFoundException::class);
        $cart->removeItem($this->getHelperProductCartItem(2));
    }

    public function testRemoveItem()
    {
        $cart = $this->cart;
        $product = $this->getHelperProductCartItem();
        $cart->addItem($product);
        $this->assertEquals(1, $cart->getItems()->count());
        $cart->removeItem($product);
        $this->assertEquals(0, $cart->getItems()->count());

        $this->expectException(ItemNotFoundException::class);
        $cart->removeItem($product);
    }

    public function testSetUserId()
    {
        $cart = $this->cart;
        $this->assertEquals(1, $cart->getUserId());
    }

    public function testPreUpdate()
    {
        $cart = $this->cart;
        $cart->preUpdate();
        $this->assertNotNull($cart);
    }

    public function testGetTotalItemsCount()
    {
        $cart = $this->cart;
        $product = $this->getHelperProductCartItem(id: 1);

        $cart->addItem($product);
        $this->assertEquals(1, $cart->getItems()->count());
        $product2 = $this->getHelperProductCartItem(id: 2);
        $cart->addItem($product2);
        $this->assertEquals(2, $cart->getTotalItemsCount());
        $product3 = $this->getHelperProductCartItem(id: 3);
        $cart->addItem($product3);
        $this->assertEquals(3, $cart->getTotalItemsCount());
        $cart->addItem($this->getHelperProductCartItem(id: 3));
        $this->assertEquals(4, $cart->getTotalItemsCount());
    }

    public function testGetItemById()
    {
        $cart = $this->cart;
        $this->assertEquals(0, $cart->getItems()->count());
        $product = $this->getHelperProductCartItem();
        $cart->addItem($product);
        $this->assertNotNull($cart->getItemById($product->getId()));
    }

    public function testItemExists()
    {
        $cart = $this->cart;
        $this->assertEquals(0, $cart->getItems()->count());
        $product = $this->getHelperProductCartItem();
        $cart->addItem($product);
        $this->assertEquals(1, $cart->getItems()->count());
        $cartItem = $cart->getItemById($product->getId());
        $this->assertTrue($cart->itemExists($cartItem));
    }

    public function testGetItem()
    {
        $cart = $this->cart;
        $this->assertEquals(0, $cart->getItems()->count());
        $product = $this->getHelperProductCartItem();
        $cart->addItem($product);
        $this->assertNotNull($cart->getItemById($product->getId()));
        $cartItem = $cart->getItemById($product->getId());
        $this->assertInstanceOf(CartItemInterface::class, $cart->getItem($cartItem));
    }

    public function testAddItem()
    {
        $cart = $this->cart;
        $product = $this->getHelperProductCartItem(id: 1);

        $cart->addItem($product);
        $this->assertEquals(1, $cart->getItems()->count());
        $product2 = $this->getHelperProductCartItem(id: 2);
        $cart->addItem($product2);
        $this->assertEquals(2, $cart->getTotalItemsCount());
    }

    public function testGetItemsPrice()
    {
        $cart = $this->cart;
        $product = $this->getHelperProductCartItem(id: 1);

        $cart->addItem($product);
        $this->assertEquals(1, $cart->getItems()->count());
        $product2 = $this->getHelperProductCartItem(id: 2);
        $cart->addItem($product2);
        $this->assertEquals(2, $cart->getTotalItemsCount());
        $this->assertEquals(200, $cart->getItemsPrice());
    }

    public function testGetId()
    {
        $cart = $this->cart;
        $this->assertEquals(1, $cart->getId());
    }

    public function testSetStatus()
    {
        $cart = $this->cart;
        $this->assertInstanceOf(CartStatus::class, $cart->getStatus());
        $this->assertEquals(CartStatus::CREATED, $cart->getStatus());
    }
}
