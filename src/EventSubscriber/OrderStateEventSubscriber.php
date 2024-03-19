<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;
use function dd;

final class OrderStateEventSubscriber implements EventSubscriberInterface
{
    /** @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>> */
    public static function getSubscribedEvents(): array
    {
        return ['workflow.payment_processing.guard' => ['guardReview']];
    }

    public function guardReview(GuardEvent $event): void
    {
        dd($event);
    }
}
