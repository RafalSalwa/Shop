<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;

trait SubscriptionPlanTrait
{
    public function getHelperSubscriptionPlan(): SubscriptionPlan
    {
        return new SubscriptionPlan(
            name: 'test',
            description: 'test description',
            tier: SubscriptionTier::Freemium,
            isActive: true,
            isVisible: true,
        );
    }
}
