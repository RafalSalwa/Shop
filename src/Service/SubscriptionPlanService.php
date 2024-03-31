<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SubscriptionPlan;
use App\Exception\ItemNotFoundException;
use App\Repository\PlanRepository;
use Psr\Log\LoggerInterface;

final readonly class SubscriptionPlanService
{
    public function __construct(
        private PlanRepository $planRepository,
        private LoggerInterface $logger,
    ) {
    }

    /** @return array<SubscriptionPlan> */
    public function fetchAvailablePlans(): array
    {
        $plans = [];

        try {
            $plans = $this->planRepository->fetchAvailablePlans();
        } catch (ItemNotFoundException $itemNotFoundException) {
            $this->logger->critical($itemNotFoundException->getMessage());
        }

        return $plans;
    }

    public function findPlanById(int $id): ?SubscriptionPlan
    {
        return $this->planRepository->find($id);
    }
}
