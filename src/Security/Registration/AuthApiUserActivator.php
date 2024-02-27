<?php

declare(strict_types=1);

namespace App\Security\Registration;

use App\Client\AuthApi\AuthApiClient;
use App\Event\UserRegisteredEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class AuthApiUserActivator implements UserActivatorInterface
{
    public function __construct(
        private AuthApiClient $authApiClient,
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function activate(string $verificationCode): bool
    {
        $this->authApiClient->confirmAccount($verificationCode);
        $user = $this->authApiClient->getByVerificationCode($verificationCode);
        $userRegisteredEvent = new UserRegisteredEvent($user);
        $this->eventDispatcher->dispatch($userRegisteredEvent);
    }
}
