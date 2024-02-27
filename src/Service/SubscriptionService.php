<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\SubscriptionPlanRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use function assert;

class SubscriptionService
{
    public function __construct(
        private readonly SubscriptionPlanRepository $subscriptionPlanRepository,
        private readonly SubscriptionRepository $subscriptionRepository,
        private readonly UserRepository $userRepository,
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
        $this->userRepository->save($user);
    }

    public function find(int $userId)
    {
        return $this->subscriptionRepository->findOneBy(['userId' => $userId]);
    }
}
