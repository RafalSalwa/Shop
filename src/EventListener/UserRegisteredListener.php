<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Subscription;
use App\Event\UserRegisteredEvent;
use App\Exception\SubscriptionPlanNotFoundException;
use App\Repository\SubscriptionPlanRepository;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredListener implements EventSubscriberInterface
{
    public function __construct(
        protected SubscriptionRepository $subscriptionRepository,
        protected SubscriptionPlanRepository $subscriptionPlanRepository
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return [UserRegisteredEvent::class => 'onRegistrationCompleted'];
    }

    /**
     * @throws SubscriptionPlanNotFoundException
     */
    public function onRegistrationCompleted(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();

        $subscriptionPlan = $this->subscriptionPlanRepository->findOneBy(['name'=> 'freemium']);
        if(null === $subscriptionPlan){
            throw new SubscriptionPlanNotFoundException("Subscription plan not found");
        }

        $subscription = new Subscription();
        $subscription->setUserId($user->getId());
        $subscription->setPlan($subscriptionPlan);
        $this->subscriptionRepository->save($subscription);

    }
}
