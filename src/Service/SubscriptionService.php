<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use App\Repository\PlanRepository;
use App\Repository\SubscriptionRepository;

final readonly class SubscriptionService
{
    public function __construct(
        private PlanRepository $subscriptionPlanRepository,
        private SubscriptionRepository $subscriptionRepository,
    ) {}

    public function cancelSubscription(int $userId): void
    {
        $this->subscriptionRepository->clearSubscriptions($userId);
        $this->assignFreemium($userId);
    }

    public function assignFreemium(int $userId): void
    {
        $subscriptionPlan = $this->subscriptionPlanRepository->createFreemiumPlan();
        $this->assignSubscription($subscriptionPlan, $userId);
    }

    public function assignSubscription(SubscriptionPlan $plan, int $userId): void
    {
        $this->subscriptionRepository->clearSubscriptions($userId);
        $subscription = new Subscription(userId: $userId, plan: $plan);
        $this->subscriptionRepository->save($subscription);
    }

    public function find(int $userId): Subscription
    {
        $subscription = $this->subscriptionRepository->findOneBy(
            [
                'userId' => $userId,
                'isActive' => true,
            ],
        );
        if (null !== $subscription) {
            return $subscription;
        }

        $this->assignFreemium($userId);

        return $this->find($userId);
    }
}
