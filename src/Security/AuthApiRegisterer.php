<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\AuthApiClient;
use App\Event\UserRegisteredEvent;
use JsonException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthApiRegisterer
{
    public function __construct(
        private readonly AuthApiClient $authApiClient,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {}

    /**
     * @throws JsonException
     */
    public function register(string $email, string $password): string
    {
        $this->authApiClient->signUp($email, $password);

        return $this->authApiClient->getVerificationCode($email, $password);
    }

    public function confirmAccount(string $verificationCode): void
    {
        $this->authApiClient->activateAccount($verificationCode);
        $user = $this->authApiClient->getByVerificationCode($verificationCode);
        $userRegisteredEvent = new UserRegisteredEvent($user);
        $this->eventDispatcher->dispatch($userRegisteredEvent);
    }

    public function getUserByCode(string $verificationCode): \App\Model\User
    {
        return $this->authApiClient->getByVerificationCode($verificationCode);
    }
}
