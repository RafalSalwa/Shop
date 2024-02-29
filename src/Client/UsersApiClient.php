<?php

declare(strict_types=1);

namespace App\Client;

use App\Entity\ShopUserInterface;
use App\Model\User;
use App\ValueObject\Token;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;
use function dd;
use function json_decode;

final readonly class UsersApiClient implements ShopUserProviderInterface
{
    public function __construct(private HttpClientInterface $usersApi)
    {}

    public function loadUserByIdentifier(string $identifier): ShopUserInterface
    {
        return $this->getUser(new Token($identifier));
    }

    private function getUser(Token $token): ShopUserInterface
    {
        try {
            $response = $this->usersApi->request(
                'GET',
                '/user',
                ['auth_bearer' => $token->value()],
            );
            $arrContent = json_decode($response->getContent(throw: true), true);

            return new User(
                id: $arrContent['user']['id'],
                email: $arrContent['user']['email'],
                authCode: $arrContent['user']['verification_token'],
                token: $arrContent['user']['token'],
                refreshToken: $arrContent['user']['refresh_token'],
            );
        } catch (
            ClientExceptionInterface | JsonException | RedirectionExceptionInterface | ServerExceptionInterface | Throwable | TransportExceptionInterface $e
        ) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString());
        }
    }

    public function loadUserByToken(Token $token): ShopUserInterface
    {
        return $this->getUser($token);
    }

    public function refreshUser(ShopUserInterface $user): ShopUserInterface
    {
        if (false === $user->getToken()->isExpired()) {
            return $this->getUser($user->getToken());
        }

        return $this->getUser($user->getRefreshToken());
    }

    public function supportsClass(string $class): void
    {
        // TODO: Implement supportsClass() method.
    }
}
