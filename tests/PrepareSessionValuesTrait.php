<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

trait PrepareSessionValuesTrait
{
    public function prepareSessionValues(array $values): void
    {
        static::getContainer()->get('event_dispatcher')->addListener(
            KernelEvents::REQUEST,
            static function (RequestEvent $requestEvent) use ($values): void {
                /** @var Session $session */
                $session = static::getContainer()->get('session.factory')->createSession();
                foreach ($values as $k => $v) {
                    $session->set($k, $v);
                }

                $requestEvent->getRequest()
                    ->setSession($session);
            }
        );
    }
}
