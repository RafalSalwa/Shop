<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Supplier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Supplier::class)]
final class SupplierTest extends TestCase
{
    public function testConstructor(): void
    {
        $id = 1;
        $name = 'Supplier Name';

        $supplier = new Supplier($id, $name);

        $this->assertSame($id, $supplier->getId());
        $this->assertSame($name, $supplier->getName());
    }

    public function testGetters(): void
    {
        $id = 1;
        $name = 'Supplier Name';

        $supplier = new Supplier($id, $name);

        $this->assertSame($id, $supplier->getId());
        $this->assertSame($name, $supplier->getName());
    }
}
