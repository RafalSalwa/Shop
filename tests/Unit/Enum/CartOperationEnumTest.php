<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\CartOperationEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: CartOperationEnum::class)]
final class CartOperationEnumTest extends TestCase
{
    public function testEnumCases(): void
    {
        // Assert the enum cases
        $this->assertSame('add', CartOperationEnum::ADD->value);
    }

    public function testAddToCart(): void
    {
        // Test the addToCart method
        $result = CartOperationEnum::addToCart();
        $this->assertSame('add', $result);
    }
}
