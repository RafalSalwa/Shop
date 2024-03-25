<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserConfirmedEvent;
use App\Event\UserRegisteredEvent;
use App\Event\UserVerificationCodeRequestEvent;
use App\Repository\PlanRepository;
use App\Repository\SubscriptionRepository;
use App\Security\EmailVerifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class UserRegistrationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SubscriptionRepository $subscriptionRepository,
        private PlanRepository $planRepository,
        private EmailVerifier $emailVerifier,
    ) {}

    /** @return array<class-string,string> */
    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onRegistrationCompleted',
            UserVerificationCodeRequestEvent::class => 'onVerificationCodeRequest',
            UserConfirmedEvent::class => 'onConfirmed',
        ];
    }

    public function onRegistrationCompleted(UserRegisteredEvent $userRegisteredEvent): void
    {
        $this->emailVerifier->sendEmailConfirmation(
            $userRegisteredEvent->getEmail(),
            $userRegisteredEvent->getConfirmationCode(),
        );
    }

    public function onVerificationCodeRequest(UserVerificationCodeRequestEvent $event): void
    {
        $this->emailVerifier->sendEmailConfirmation(
            $event->getEmail(),
            $event->getConfirmationCode(),
        );
    }

    public function onConfirmed(UserConfirmedEvent $event): void
    {
        $plan = $this->planRepository->createFreemiumPlan();
        $this->subscriptionRepository->assignSubscription($event->getUserId(), $plan);
    }
}
