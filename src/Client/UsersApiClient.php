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
    public function __construct(private readonly HttpClientInterface $usersApi)
    {}

    public function getUser(ApiTokenPair $tokenPair): User
    {
        try {
            $response = $this->usersApi->request(
                'GET',
                '/user',
                ['auth_bearer' => $tokenPair->getToken()],
            );
        } catch (ClientExceptionInterface) {
        } catch (RedirectionExceptionInterface) {
        } catch (ServerExceptionInterface) {
        } catch (TransportExceptionInterface) {
        } catch (JsonException) {
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString());
        }

        $user = new User();
        $user->setFromAuthApi($response);
        $user->setToken($tokenPair->getToken())->setRefreshToken($tokenPair->getRefreshToken());

        return $user;
    }
}
