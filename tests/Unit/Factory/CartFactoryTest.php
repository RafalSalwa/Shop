<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\Entity\Cart;
use App\Factory\CartFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: CartFactory::class)]
#[UsesClass(className: Cart::class)]
final class CartFactoryTest extends TestCase
{
    public function testCreateCart(): void
    {
        $cartFactory = new CartFactory();

        $createdCart = $cartFactory->create(123);

        // Assertions
        $this->assertInstanceOf(Cart::class, $createdCart);
    }
}
