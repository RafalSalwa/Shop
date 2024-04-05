<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\AbstractCartItem;
use App\Entity\Cart;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Product;
use App\Entity\ProductCartItem;
use App\Entity\SubscriptionPlan;
use App\Enum\CartItemTypeEnum;
use App\Enum\SubscriptionTier;
use App\Tests\Helpers\CartHelperTrait;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCartItem::class)]
#[CoversClass(ProductCartItem::class)]
#[UsesClass(Cart::class)]
#[UsesClass(Product::class)]
#[UsesClass(SubscriptionTier::class)]
#[UsesClass(SubscriptionPlan::class)]
final class AbstractCartItemTest extends TestCase
{
    use CartHelperTrait;
    use ProductHelperCartItemTrait;

    private Cart $cart;

    private CartItemInterface $productCartItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cart = $this->getHelperCart(id: 1);
        $this->productCartItem = $this->getHelperProductCartItem(id: 1);
    }

    public function testConstructor(): void
    {
        $cart = $this->cart;
        $cartItem = $this->productCartItem;
        $cartItem->setCart($cart);

        $this->assertSame($cart, $cartItem->getCart());
        // Default quantity should be 1
        $this->assertSame(1, $cartItem->getQuantity());
    }

    public function testGetters(): void
    {
        $cart = $this->cart;
        $cartItem = $this->productCartItem;
        $cartItem->setCart($cart);

        $this->assertSame($cart, $cartItem->getCart());
        $this->assertSame(1, $cartItem->getQuantity());
        $this->assertSame(1, $cartItem->getId());
        $this->assertSame(AbstractCartItem::class, $cartItem->getItemType());
        $this->assertSame(CartItemTypeEnum::product->value, $cartItem->getType());
    }

    public function testUpdateQuantity(): void
    {
        $cartItem = $this->productCartItem;

        $cartItem->updateQuantity(5);

        $this->assertSame(5, $cartItem->getQuantity());
    }
}
