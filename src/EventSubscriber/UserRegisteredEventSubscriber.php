<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use App\Exception\SubscriptionPlanNotFoundException;
use App\Repository\SubscriptionPlanRepository;
use App\Repository\SubscriptionRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UserRegisteredEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected SubscriptionRepository $subscriptionRepository,
        protected SubscriptionPlanRepository $subscriptionPlanRepository,
    ) {}

    /** @return array<class-string,string> */
    public static function getSubscribedEvents(): array
    {
        return [UserRegisteredEvent::class => 'onRegistrationCompleted'];
    }

    /** @throws SubscriptionPlanNotFoundException */
    public function onRegistrationCompleted(UserRegisteredEvent $userRegisteredEvent): void
    {
//        $user = $userRegisteredEvent->getEmail();
//
//        $subscriptionPlan = $this->subscriptionPlanRepository->findOneBy(['name' => 'freemium']);
//        if (null === $subscriptionPlan) {
//            throw new SubscriptionPlanNotFoundException('Subscription plan not found');
//        }
//
//        $subscription = new Subscription();
//        $subscription->setUserId($user->getId());
//        $subscription->setPlan($subscriptionPlan);
//
//        $this->subscriptionRepository->save($subscription);
    }
}
