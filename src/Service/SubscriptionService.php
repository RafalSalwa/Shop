<?php

namespace App\Service;

use App\Repository\PlanRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class SubscriptionService
{
    private SubscriptionRepository $subscriptionRepository;
    private PlanRepository $planRepository;
    private UserRepository $userRepository;
    private Security $security;


    public function __construct(SubscriptionRepository $subscriptionRepository, PlanRepository $planRepository, UserRepository $userRepository, Security $security)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->planRepository = $planRepository;
        $this->userRepository = $userRepository;
        $this->security = $security;
    }

    public function cancelSubscription()
    {
        $this->assignSubscription("Freemium");
    }

    public function assignSubscription(string $type)
    {
        $plan = $this->planRepository->getByName($type);
        $user = $this->security->getUser();
        $subscription = $user->getSubscription();

        $user->setSubscription($subscription);

        $this->userRepository->save($user);
    }
}
