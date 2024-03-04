<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\SubscriptionPlanRepository;

final readonly class SubscriptionPlanService
{
    public function __construct(private SubscriptionPlanRepository $subscriptionPlanRepository)
    {
    }

    public function fetchAvailablePlans()
    {
        return $this->subscriptionPlanRepository->fetchAvailablePlans();
    }
}
