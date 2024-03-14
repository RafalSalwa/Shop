<?php

declare(strict_types=1);

namespace App\EventSubscriber\KernelEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class AccessDeniedListener implements EventSubscriberInterface
{
    /** @inheritDoc */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => ['onKernelException', 2]];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (false === $exception instanceof AccessDeniedException) {
            return;
        }
        // ... perform some action (e.g. logging)

        // optionally set the custom response
        $event->setResponse(new Response(null, 403));

        // or stop propagation (prevents the next exception listeners from being called)
        //$event->stopPropagation();
    }
}
