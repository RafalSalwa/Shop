<?php

declare(strict_types=1);

namespace App\EventSubscriber\KernelEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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

        $event->setResponse(new Response(null, Response::HTTP_FORBIDDEN));
    }
}
