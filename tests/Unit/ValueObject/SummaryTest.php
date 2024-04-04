<?php

declare(strict_types=1);

namespace App\Tests\Unit\ValueObject;

use App\ValueObject\Summary;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Summary::class)]
final class SummaryTest extends TestCase
{
    public function testGetNet(): void
    {
        $summary = new Summary(net: 100, discount: 0, tax: 10, shipping: 5);
        $this->assertSame(100, $summary->getNet());
    }

    public function testGetTax(): void
    {
        $summary = new Summary(net: 100, discount: 0, tax: 10, shipping: 5);
        $this->assertSame(10, $summary->getTax());
    }

    public function testGetShipping(): void
    {
        $summary = new Summary(net: 100, discount: 0, tax: 10, shipping: 5);
        $this->assertSame(5, $summary->getShipping());
    }

    public function testGetSubTotalWithoutDiscount(): void
    {
        $summary = new Summary(net: 100, discount: 0, tax: 10, shipping: 5);
        $this->assertSame(110, $summary->getSubTotal());
    }

    public function testGetSubTotalWithDiscount(): void
    {
        $summary = new Summary(net: 100, discount: 20, tax: 10, shipping: 5);
        $this->assertSame(90, $summary->getSubTotal());
    }

    public function testGetTotal(): void
    {
        $summary = new Summary(net: 100, discount: 20, tax: 10, shipping: 5);
        $this->assertSame(95, $summary->getTotal());
    }

    public function testGetDiscount(): void
    {
        $summary = new Summary(net: 100, discount: 20, tax: 10, shipping: 5);
        $this->assertSame('0.20', $summary->getDiscount());
    }
}
