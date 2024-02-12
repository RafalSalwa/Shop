<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\SubscriptionPlanRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class SubscriptionService
{
    public function __construct(
        private readonly SubscriptionPlanRepository $planRepository,
        private readonly UserRepository             $userRepository,
        private readonly Security                   $security,
    ) {
    }

    public function cancelSubscription(): void
    {
        $this->assignSubscription('Freemium');
    }

    public function assignSubscription(string $type): void
    {
        $plan = $this->planRepository->getByName($type);
        if (! $plan) {
            return;
        }
        /**
 * @var User $user 
*/
        $user = $this->security->getUser();
        $subscription = $user->getSubscription();
        if ($user->getSubscription()->getSubscriptionPlan()->getName() === $type) {
            return;
        }
        $subscription->setSubscriptionPlan($plan);
        $user->setSubscription($subscription);
        $this->userRepository->save($user);
    }
}
