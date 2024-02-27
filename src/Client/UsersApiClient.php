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

final readonly class UsersApiClient
{
    public function __construct(private HttpClientInterface $usersApi)
    {}

    public function getUser(ApiTokenPair $apiTokenPair): User
    {
        try {

            $response = $this->usersApi->request(
                'GET',
                '/user',
                ['auth_bearer' => $apiTokenPair->getToken()],
            );
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface | JsonException) {
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString());
        }

        $user = new User();
        $user->setFromAuthApi($response);
        $user->setToken($apiTokenPair->getToken())->setRefreshToken($apiTokenPair->getRefreshToken());

        return $user;
    }

    public function byIdentifier(string $identifier): User
    {
        try {
            $response = $this->usersApi->request(
                'GET',
                '/user',
                ['auth_bearer' => $identifier],
            );
            $user = new User();
            $user->setFromAuthApi($response);
            $user->getTokenPair();

            return $user;
        } catch (TransportExceptionInterface $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface) {
        } catch (Exception $ex) {
            dd($ex->getMessage(), $ex->getTraceAsString(), $response);
        }
    }
}
