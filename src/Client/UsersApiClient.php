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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
    public function loadUserByIdentifier(string $identifier): UserInterface
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
            $arrContent = json_decode($response->getContent(), true, JSON_THROW_ON_ERROR);

            $user = new User(id: $arrContent['user']['id'], email: $arrContent['user']['email']);
            $user->setToken(new Token($arrContent['user']['token']));
            $user->setRefreshToken(new Token($arrContent['user']['refresh_token']));
            $subscription = $this->subscriptionService->findForUser($user->getId());

            $user->setSubscription($subscription);

            return $user;
        } catch (ClientExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw AuthApiErrorFactory::create($exception);
        } catch (TransportExceptionInterface | JsonException $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function loadUserByToken(Token $token): ShopUserInterface
    {
        return $this->getUser($token);
    }

    public function refreshUser(ShopUserInterface $user): ShopUserInterface
    {
        if (true === $user->getToken()->isExpired()) {
            $user = $this->getUser($user->getRefreshToken());
        }

        return $this->getUser($user->getToken());
    }
}
