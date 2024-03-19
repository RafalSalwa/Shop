<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SubscriptionPlan;
use App\Repository\SubscriptionPlanRepository;
use Doctrine\ORM\NonUniqueResultException;

final readonly class SubscriptionPlanService
{
    public function __construct(private SubscriptionPlanRepository $subscriptionPlanRepository)
    {
    }

    /** @return array<SubscriptionPlan>|null */
    public function fetchAvailablePlans(): ?array
    {
        return $this->subscriptionPlanRepository->fetchAvailablePlans();
    }

    /** @throws NonUniqueResultException */
    public function findPlanById(int $id): ?SubscriptionPlan
    {
        return $this->subscriptionPlanRepository->findById($id);
    }
}
