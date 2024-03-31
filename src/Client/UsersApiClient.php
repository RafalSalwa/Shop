<?php

declare(strict_types=1);

namespace App\Client;

use App\Client\Contracts\ShopUserProviderInterface;
use App\Entity\Contracts\ShopUserInterface;
use App\Exception\AuthApiErrorFactory;
use App\Exception\AuthApiRuntimeException;
use App\Exception\Contracts\AuthenticationExceptionInterface;
use App\Model\User;
use App\Service\SubscriptionService;
use App\ValueObject\Token;
use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function assert;
use function json_decode;

use const JSON_THROW_ON_ERROR;

final readonly class UsersApiClient implements ShopUserProviderInterface
{
    public function __construct(
        private HttpClientInterface $usersApi,
        private LoggerInterface $logger,
        private SubscriptionService $subscriptionService,
    ) {
    }

    /** @throws AuthenticationExceptionInterface */
    public function loadUserByIdentifier(string $identifier): ShopUserInterface
    {
        return $this->getUser(new Token($identifier));
    }

    /** @throws AuthenticationExceptionInterface */
    private function getUser(Token $token): User
    {
        try {
            $response = $this->usersApi->request(
                'GET',
                '/user',
                ['auth_bearer' => $token->value()],
            );
            $arrContent = json_decode(
                json: $response->getContent(),
                associative: true,
                depth: 512,
                flags: JSON_THROW_ON_ERROR,
            );

            $user = new User(email: $arrContent['user']['email']);
            $user->setToken(new Token($arrContent['user']['token']));
            $user->setRefreshToken(new Token($arrContent['user']['refresh_token']));
            $subscription = $this->subscriptionService->findForUser($user->getId());

            $user->setSubscription($subscription);

            return $user;
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw AuthApiErrorFactory::create($exception);
        } catch (JsonException | TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function loadUserByToken(Token $token): ShopUserInterface
    {
        return $this->getUser($token);
    }

    /** @throws AuthenticationExceptionInterface */
    public function refreshUser(ShopUserInterface $user): ShopUserInterface
    {
        assert($user->getToken() instanceof Token);
        if (false === $user->getToken()->isExpired()) {
            return $this->getUser($user->getToken());
        }

        assert($user->getRefreshToken() instanceof Token);

        return $this->getUser($user->getRefreshToken());
    }
}
