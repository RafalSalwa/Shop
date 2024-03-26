<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Payment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\TransitionBlocker;

use function assert;
use function in_array;

final class OrderStateEventSubscriber implements EventSubscriberInterface
{
    /** @return array<string, array{0: string, 1: int}|list<array{0: string, 1?: int}>|string> */
    public static function getSubscribedEvents(): array
    {
        return ['workflow.payment_processing.guard' => ['guardReview']];
    }

    public function guardReview(GuardEvent $event): void
    {
        $payment = $event->getSubject();
        assert($payment instanceof Payment);

        $transition = $event->getTransition();
        if (false === in_array($payment->getStatus(), $transition->getFroms(), true)) {
            $event->addTransitionBlocker(new TransitionBlocker('Wrong transition state', '0'));
        }

        if (0 === $payment->getAmount()) {
            return;
        }

        $event->addTransitionBlocker(new TransitionBlocker('Cannot submit payment with 0 amount', '0'));
    }
}
