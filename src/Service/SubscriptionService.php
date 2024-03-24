<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use App\Repository\PlanRepository;
use App\Repository\SubscriptionRepository;

use function array_key_exists;

final class SubscriptionService
{
    /** @var array<int, Subscription> */
    private array $memCache = [];

    public function __construct(
        private readonly PlanRepository $planRepository,
        private readonly SubscriptionRepository $subscriptionRepository,
    ) {}

    public function cancelSubscription(int $userId): void
    {
        $this->subscriptionRepository->clearSubscriptions($userId);
        $this->assignFreemium($userId);
    }

    public function assignFreemium(int $userId): void
    {
        $subscriptionPlan = $this->planRepository->createFreemiumPlan();
        $this->assignSubscription($subscriptionPlan, $userId);
    }

    public function assignSubscription(SubscriptionPlan $plan, int $userId): void
    {
        $this->subscriptionRepository->clearSubscriptions($userId);
        $subscription = new Subscription(userId: $userId, plan: $plan);
        $this->subscriptionRepository->save($subscription);
    }

    public function findForUser(int $userId): Subscription
    {
        if (true === array_key_exists($userId, $this->memCache)) {
            return $this->memCache[$userId];
        }
        $subscription = $this->subscriptionRepository->findOneBy(
            [
                'userId' => $userId,
                'isActive' => true,
            ],
        );
        if (null !== $subscription) {
            $this->memCache[$userId] = $subscription;

            return $subscription;
        }
        $this->assignFreemium($userId);

        return $this->findForUser($userId);
    }
}
