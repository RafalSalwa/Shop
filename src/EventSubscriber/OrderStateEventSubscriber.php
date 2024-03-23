<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Payment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\TransitionBlocker;
use function assert;

final class OrderStateEventSubscriber implements EventSubscriberInterface
{
    /** @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>> */
    public static function getSubscribedEvents(): array
    {
        return ['workflow.payment_processing.guard' => ['guardReview']];
    }

    public function guardReview(GuardEvent $event): void
    {
        $payment = $event->getSubject();
        assert($payment instanceof Payment);

        $transition = $event->getTransition();
        if ($payment->getStatus() !== $transition->getFroms()) {
            $event->addTransitionBlocker(new TransitionBlocker('Wrong transition state', '0'));
        }
        if (null !== $payment->getAmount()) {
            return;
        }

        $event->addTransitionBlocker(new TransitionBlocker('Cannot submit payment with 0 amount', '0'));
    }
}
