<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;
use App\Tests\Helpers\ProtectedPropertyTrait;
use App\Tests\Helpers\SubscriptionPlanTrait;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: SubscriptionPlan::class)]
#[UsesClass(className: SubscriptionTier::class)]
final class SubscriptionPlanTest extends TestCase
{
    use ProtectedPropertyTrait;
    use SubscriptionPlanTrait;

    private SubscriptionPlan $subscriptionPlan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subscriptionPlan = $this->getHelperSubscriptionPlan();
        $this->setProtectedProperty($this->subscriptionPlan, 'id', 123);
    }

    public function testConstructor(): void
    {
        $plan = $this->getHelperSubscriptionPlan();

        $this->assertSame('test', $plan->getName());
        $this->assertSame('test description', $plan->getDescription());
        $this->assertTrue($plan->isActive());
        $this->assertTrue($plan->isVisible());
        $this->assertSame(0, $plan->getPrice());
        $this->assertSame(SubscriptionTier::Freemium->value(), $plan->getTier());

        $dateTimeImmutable = new DateTimeImmutable();
        $this->assertEqualsWithDelta($dateTimeImmutable->getTimestamp(), $plan->getCreatedAt()->getTimestamp(), 1);
    }

    public function testGetters(): void
    {
        $plan = $this->getHelperSubscriptionPlan();

        $this->assertSame('test description', $plan->getDescription());
        $this->assertTrue($plan->isActive());
        $this->assertTrue($plan->isVisible());
        $this->assertSame(0, $plan->getPrice());
        $this->assertSame(SubscriptionTier::Freemium->value(), $plan->getTier());
    }

    public function testId(): void
    {
        $plan = $this->subscriptionPlan;
        $this->assertSame(123, $plan->getId());
    }
}
