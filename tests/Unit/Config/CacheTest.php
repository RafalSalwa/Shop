<?php

declare(strict_types=1);

namespace App\Tests\Unit\Config;

use App\Config\Cache;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Cache::class)]
final class CacheTest extends TestCase
{
    public function testMaxAgeConstant(): void
    {
        // Assert that the MAX_AGE constant has the expected value (3,600 seconds)
        $this->assertSame(3600, Cache::MAX_AGE);
    }

    public function testDefaultTtlConstant(): void
    {
        // Assert that the DEFAULT_TTL constant has the expected value (86,400 seconds)
        $this->assertSame(86400, Cache::DEFAULT_TTL);
    }
}
