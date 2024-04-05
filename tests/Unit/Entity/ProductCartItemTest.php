<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\AbstractCartItem;
use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\ProductCartItem;
use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: ProductCartItem::class)]
#[CoversClass(className: AbstractCartItem::class)]

#[UsesClass(className: Product::class)]
#[UsesClass(className: Cart::class)]
#[UsesClass(className: AbstractCartItem::class)]
#[UsesClass(className: SubscriptionTier::class)]
#[UsesClass(className: SubscriptionPlan::class)]
final class ProductCartItemTest extends TestCase
{
    use ProductHelperCartItemTrait;

    private Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();

        $cartItem = $this->getHelperProductCartItem(1);

        $this->cart = $cartItem->getCart();
    }

    public function testConstructor(): void
    {
        $cart = $this->cart;
        $product = new Product('Test Product', '1 item', 10_00, 10, 5);
        $quantity = 3;

        $productCartItem = new ProductCartItem($cart, $product, $quantity);

        $this->assertSame($cart, $productCartItem->getCart());
        $this->assertSame($product, $productCartItem->getReferencedEntity());
        $this->assertSame($quantity, $productCartItem->getQuantity());
    }

    public function testGetName(): void
    {
        $cart = $this->cart;
        $product = new Product('Test Product', '1 item', 10_00, 10, 5);
        $quantity = 3;

        $productCartItem = new ProductCartItem($cart, $product, $quantity);

        $this->assertSame('Test Product', $productCartItem->getName());
    }
}
