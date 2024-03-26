<?php

declare(strict_types=1);

namespace App\EventSubscriber\KernelEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class HttpExceptionEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {}

    /** @return array<string, array{0: string, 1: int}> */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['onKernelException', 2]];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (false === $exception instanceof HttpException) {
            return;
        }

        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('login_index')));
    }
}
