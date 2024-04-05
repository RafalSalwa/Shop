<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event;

use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;
use App\Event\StockDepletedEvent;
use App\Tests\Helpers\ProductHelperCartItemTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: StockDepletedEvent::class)]
#[UsesClass(className: Product::class)]
#[UsesClass(className: SubscriptionTier::class)]
#[UsesClass(className: SubscriptionPlan::class)]
final class StockDepletedEventTest extends TestCase
{
    use ProductHelperCartItemTrait;

    public function testGetItem(): void
    {
        $product = $this->getHelperProduct(1);
        $stockDepletedEvent = new StockDepletedEvent($product);
        $this->assertSame($product, $stockDepletedEvent->getItem());
    }
}
