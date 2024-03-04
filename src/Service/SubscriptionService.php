<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\SubscriptionPlanRepository;
use App\Repository\SubscriptionRepository;
use Symfony\Bundle\SecurityBundle\Security;
use function assert;

final class SubscriptionService
{
    public function __construct(
        private readonly SubscriptionPlanRepository $subscriptionPlanRepository,
        private readonly SubscriptionRepository $subscriptionRepository,
        private readonly Security $security,
    ) {}

    public function cancelSubscription(): void
    {
        $this->assignSubscription('Freemium');
    }

    public function assignSubscription(string $type): void
    {
        $plan = $this->subscriptionPlanRepository->getByName($type);
        if (! $plan) {
            return;
        }

        $user = $this->security->getUser();
        assert($user instanceof User);
        $subscription = $user->getSubscription();
        if ($user->getSubscription()->getSubscriptionPlan()->getName() === $type) {
            return;
        }

        $subscription->setSubscriptionPlan($plan);
        $user->setSubscription($subscription);
    }

    public function find(int $userId): ?Subscription
    {
        return $this->subscriptionRepository->findOneBy(['userId' => $userId]);
    }
}
