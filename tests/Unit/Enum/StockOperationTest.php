<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\StockOperation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: StockOperation::class)]
final class StockOperationTest extends TestCase
{
    public function testEnumCases(): void
    {
        // Assert the enum cases
        $this->assertSame('increase', StockOperation::Increase->value);
        $this->assertSame('decrease', StockOperation::Decrease->value);
    }

    public function testIncrease(): void
    {
        // Test the increase method
        $result = StockOperation::increase();
        $this->assertSame('increase', $result);
    }

    public function testDecrease(): void
    {
        // Test the decrease method
        $result = StockOperation::decrease();
        $this->assertSame('decrease', $result);
    }
}
