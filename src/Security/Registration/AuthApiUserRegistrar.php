<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\AuthApiClient;
use App\Event\UserRegisteredEvent;
use App\Model\User;
use JsonException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class AuthApiRegisterer
{
    public function __construct(
        private readonly AuthApiClient $authApiClient,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EmailVerifier $emailVerifier,
    ) {}

    public function register(string $email, string $password): string
    {
        try {
            $this->authApiClient->signUp($email, $password);

            $this->eventDispatcher->dispatch(new UserRegisteredEvent($email));
            $verificationCode = $this->authApiClient->getVerificationCode($email, $password);
            $this->emailVerifier->sendEmailConfirmation($user->getEmail(), $verificationCode);
        } catch (JsonException) {
        }
    }

    public function confirmAccount(string $verificationCode): void
    {
        $this->authApiClient->activateAccount($verificationCode);
        $user = $this->authApiClient->getByVerificationCode($verificationCode);
        $userRegisteredEvent = new UserRegisteredEvent($user);
        $this->eventDispatcher->dispatch($userRegisteredEvent);
    }

    public function getUserByCode(string $verificationCode): User
    {
        return $this->authApiClient->getByVerificationCode($verificationCode);
    }
}
