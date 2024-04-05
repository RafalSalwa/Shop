<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Product::class)]
#[UsesClass(className: SubscriptionTier::class)]
#[UsesClass(className: SubscriptionPlan::class)]
final class ProductTest extends TestCase
{
    use ProductHelperCartItemTrait;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = $this->getHelperProduct(1);
    }

    public function testConstructor(): void
    {
        $name = 'Test Product';
        $quantityPerUnit = '1 item';
        $price = 10_00;
        $unitsInStock = 10;
        $unitsOnOrder = 5;

        $product = new Product($name, $quantityPerUnit, $price, $unitsInStock, $unitsOnOrder);

        $this->assertSame($name, $product->getName());
        $this->assertSame($quantityPerUnit, $product->getQuantityPerUnit());
        $this->assertSame($price, $product->getPrice());
        $this->assertSame($unitsInStock, $product->getUnitsInStock());
        $this->assertSame($unitsOnOrder, $product->getUnitsOnOrder());
        $this->assertSame('product', $product->getTypeName());
        // Assuming SubscriptionPlan is a dependency, you may need to mock it and test accordingly
    }

    public function testSettersAndGetters(): void
    {
        $product = new Product('Test Product', '1 item', 10_00, 10, 5);

        $unitsInStock = 15;
        $unitsOnOrder = 3;

        $product->setUnitsInStock($unitsInStock);
        $product->setUnitsOnOrder($unitsOnOrder);

        $this->assertSame($unitsInStock, $product->getUnitsInStock());
        $this->assertSame($unitsOnOrder, $product->getUnitsOnOrder());
        $this->assertSame(1, $this->product->getId());
    }

    /** @psalm-suppress DocblockTypeContradiction */
    public function testStockManagement(): void
    {
        $product = new Product('Test Product', '1 item', 10_00, 10, 5);

        $product->decreaseStock(2);
        $this->assertSame(8, $product->getUnitsInStock());

        $product->increaseStock(3);
        $this->assertSame(11, $product->getUnitsInStock());

        $product->changeStock(20);
        $this->assertSame(20, $product->getUnitsInStock());
    }

    public function testDisplayName(): void
    {
        $product = new Product('Test Product', '1 item', 10_00, 10, 5);

        $this->assertSame('product Test Product', $product->getDisplayName());
    }

    public function testGrossPrice(): void
    {
        $product = new Product('Test Product', '1 item', 10_00, 10, 5);

        // Assuming the gross price calculation is correct
        $expectedGrossPrice = '12.30';
        $this->assertSame($expectedGrossPrice, $product->getGrossPrice());
    }
}
