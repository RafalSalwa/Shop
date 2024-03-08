<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;
use function dd;

final class OrderStateEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return ['workflow.guard' => ['guardReview']];
    }

    public function guardReview(GuardEvent $event): void
    {
        dd($event);
    }
}
