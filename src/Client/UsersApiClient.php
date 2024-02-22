<?php

declare(strict_types=1);

namespace App\Client;

use App\Model\ApiTokenPair;
use App\Model\User;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;
use function dd;

class UsersApiClient
{
    public function __construct(private readonly HttpClientInterface $httpClient)
    {}

    public function getUser(ApiTokenPair $apiTokenPair): User
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                '/user',
                ['auth_bearer' => $apiTokenPair->getToken()],
            );
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface|JsonException) {
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString());
        }

        $user = new User();
        $user->setFromAuthApi($response);
        $user->setToken($apiTokenPair->getToken())->setRefreshToken($apiTokenPair->getRefreshToken());

        return $user;
    }
}
