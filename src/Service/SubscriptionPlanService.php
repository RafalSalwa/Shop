<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SubscriptionPlan;
use App\Repository\PlanRepository;

final readonly class SubscriptionPlanService
{
    public function __construct(private PlanRepository $planRepository)
    {
    }

    /** @return array<SubscriptionPlan>|null */
    public function fetchAvailablePlans(): ?array
    {
        return $this->planRepository->fetchAvailablePlans();
    }

    public function findPlanById(int $id): ?SubscriptionPlan
    {
        return $this->planRepository->find($id);
    }
}
