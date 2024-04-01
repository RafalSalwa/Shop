<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\UsersApiClient;
use App\Entity\Contracts\ShopUserInterface;
use App\Exception\Contracts\AuthenticationExceptionInterface;
use App\Repository\SubscriptionRepository;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use function assert;
use function is_subclass_of;

/**
 * @template-covariant TUser of ShopUserInterface
 * @template-implements UserProviderInterface<ShopUserInterface>
 */
final readonly class AuthApiUserProvider implements UserProviderInterface
{
    public function __construct(
        private UsersApiClient $apiClient,
        private SubscriptionRepository $subscriptionRepository,
    ) {}

    public function supportsClass(string $class): bool
    {
        return is_subclass_of($class, ShopUserInterface::class);
    }

    /** @throws AuthenticationExceptionInterface */
    public function refreshUser(UserInterface $user): ShopUserInterface
    {
        assert($user instanceof ShopUserInterface);
        if (true === $user->getToken()?->isExpired() && true === $user->getRefreshToken()?->isExpired()) {
            throw new CredentialsExpiredException('SessionStorage Expired, please login again.');
        }

        $user = $this->apiClient->refreshUser($user);
        $subscription = $this->subscriptionRepository->findForUser($user->getId());
        $user->setSubscription($subscription);

        return $user;
    }

    /**
     * @param string $identifier JWTToken string
     *
     * @throws AuthenticationExceptionInterface
     */
    public function loadUserByIdentifier(string $identifier): ShopUserInterface
    {
        return $this->apiClient->loadUserByIdentifier($identifier);
    }
}
