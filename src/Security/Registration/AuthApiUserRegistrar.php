<?php

declare(strict_types=1);

namespace App\Security\Registration;

use App\Client\AuthApiClient;
use App\Event\UserConfirmedEvent;
use App\Event\UserVerificationCodeRequestEvent;
use App\Exception\AuthApiRuntimeException;
use App\Security\Contracts\UserRegistrarInterface;
use App\Security\EmailVerifier;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class AuthApiUserRegistrar implements UserRegistrarInterface
{
    public function __construct(
        private AuthApiClient $authApiClient,
        private EventDispatcherInterface $eventDispatcher,
        private EmailVerifier $emailVerifier,
    ) {}

    /** @throws AuthApiRuntimeException */
    public function register(string $email, string $password): void
    {
        $this->authApiClient->signUp($email, $password);
        $verificationCode = $this->authApiClient->getVerificationCode($email);
        $this->eventDispatcher->dispatch(new UserVerificationCodeRequestEvent($email, $verificationCode));
    }

    public function sendVerificationCode(string $email): void
    {
        $verificationCode = $this->authApiClient->getVerificationCode($email);
        $this->eventDispatcher->dispatch(new UserVerificationCodeRequestEvent($email, $verificationCode));
        $this->emailVerifier->sendEmailConfirmation($email, $verificationCode);
    }

    public function confirm(string $verificationCode): void
    {
        $this->authApiClient->confirmAccount($verificationCode);
        $user = $this->authApiClient->getByVerificationCode($verificationCode);
        $this->eventDispatcher->dispatch(new UserConfirmedEvent($user->getId()));
    }
}
