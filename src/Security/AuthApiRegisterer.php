<?php

namespace App\Security;

use App\Client\AuthApiClient;
use App\Event\StockDepletedEvent;
use App\Event\UserRegisteredEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthApiRegisterer {

    public function __construct(
        private readonly AuthApiClient $authApiClient,
        private readonly HttpClientInterface $usersApi,
        private readonly EventDispatcherInterface $eventDispatcher,
    ){}

    /**
     * @throws \JsonException
     */
    public function register(string $email, string $password):string
    {
        $this->authApiClient->signUp($email,$password);
        return $this->authApiClient->getVerificationCode($email,$password);
    }

    public function confirmAccount(string $verificationCode)
    {
        $this->authApiClient->activateAccount($verificationCode);
        $user = $this->authApiClient->getByVerificationCode($verificationCode);
        $event = new UserRegisteredEvent($user);
        $this->eventDispatcher->dispatch($event);
    }

    public function getUserByCode(string $verificationCode)
    {
        return $this->authApiClient->getByVerificationCode($verificationCode);
    }
}
