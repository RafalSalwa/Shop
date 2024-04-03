<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use function assert;

trait PrepareSessionValuesTrait
{
    /** @param array<array-key, string> $values */
    public function prepareSessionValues(array $values): void
    {
        static::getContainer()->get('event_dispatcher')->addListener(
            KernelEvents::REQUEST,
            static function (RequestEvent $requestEvent) use ($values): void {
                $session = static::getContainer()->get('session.factory')->createSession();
                assert($session instanceof Session);
                foreach ($values as $k => $v) {
                    $session->set($k, $v);
                }

                $requestEvent->getRequest()
                    ->setSession($session)
                ;
            },
        );
    }
}
