<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use App\Enum\CartItemTypeEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use ValueError;

#[CoversClass(className: CartItemTypeEnum::class)]
#[UsesClass(className: Product::class)]
#[UsesClass(className: SubscriptionPlan::class)]
final class CartItemTypeEnumTest extends TestCase
{
    public function testEnumCases(): void
    {
        // Assert the enum cases
        $this->assertSame(Product::class, CartItemTypeEnum::product->value);
        $this->assertSame(CartItemTypeEnum::subscription->value, SubscriptionPlan::class);
    }

    public function testTryFromNameValid(): void
    {
        // Test tryFromName with valid name
        $result = CartItemTypeEnum::tryFromName('product');
        $this->assertSame(Product::class, $result);

        $result = CartItemTypeEnum::tryFromName('subscription');
        $this->assertSame(SubscriptionPlan::class, $result);
    }

    public function testTryFromNameInvalid(): void
    {
        // Test tryFromName with invalid name
        $result = CartItemTypeEnum::tryFromName('invalid');
        $this->assertNull($result);
    }

    public function testFromNameValid(): void
    {
        // Test fromName with valid name
        $result = CartItemTypeEnum::fromName('product');
        $this->assertSame(Product::class, $result);

        $result = CartItemTypeEnum::fromName('subscription');
        $this->assertSame(SubscriptionPlan::class, $result);
    }

    public function testFromNameInvalid(): void
    {
        // Test fromName with invalid name (should throw ValueError)
        $this->expectException(ValueError::class);
        CartItemTypeEnum::fromName('invalid');
    }
}
