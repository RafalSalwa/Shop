<?php

declare(strict_types=1);

namespace App\Security\Registration;

use App\Client\AuthApiClient;
use App\Event\UserConfirmedEvent;
use App\Event\UserRegisteredEvent;
use App\Event\UserVerificationCodeRequestEvent;
use App\Security\EmailVerifier;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class AuthApiUserRegistrar implements UserRegistrarInterface
{
    public function __construct(
        private AuthApiClient $authApiClient,
        private EventDispatcherInterface $eventDispatcher,
        private EmailVerifier $emailVerifier,
    ) {}

    public function register(string $email, string $password): void
    {
        $this->authApiClient->signUp($email, $password);
        $this->eventDispatcher->dispatch(new UserRegisteredEvent($email));
    }

    public function sendVerificationCode(string $email): void
    {
        $verificationCode = $this->authApiClient->getVerificationCode($email);
        $this->eventDispatcher->dispatch(new UserVerificationCodeRequestEvent($email));
        $this->emailVerifier->sendEmailConfirmation($email, $verificationCode);
    }

    public function confirm(string $verificationCode): void
    {
        $this->authApiClient->confirmAccount($verificationCode);
        $this->eventDispatcher->dispatch(new UserConfirmedEvent($verificationCode));
    }
}
