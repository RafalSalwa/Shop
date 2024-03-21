<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SubscriptionPlan;
use App\Repository\PlanRepository;
use Doctrine\ORM\NonUniqueResultException;

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

    /** @throws NonUniqueResultException */
    public function findPlanById(int $id): ?SubscriptionPlan
    {
        return $this->planRepository->findById($id);
    }
}
