<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SubscriptionPlan;
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

    public function findPlanById(int $id): SubscriptionPlan
    {
        return $this->subscriptionPlanRepository->findById($id);
    }
}
