<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\SubscriptionTier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: SubscriptionTier::class)]
final class SubscriptionTierTest extends TestCase
{
    public function testEnumCases(): void
    {
        // Assert the enum cases and their associated values
        $this->assertSame(1, SubscriptionTier::Freemium->value());
        $this->assertSame(10, SubscriptionTier::Plus->value());
        $this->assertSame(20, SubscriptionTier::Gold->value());
        $this->assertSame(30, SubscriptionTier::VIP->value());
        $this->assertSame(50, SubscriptionTier::FIVE_HUNDRED->value());
        $this->assertSame(80, SubscriptionTier::EIGHT_HUNDRED->value());
    }
}
