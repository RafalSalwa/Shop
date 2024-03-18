<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\UsersApiClient;
use App\Entity\Contracts\ShopUserInterface;
use App\Repository\SubscriptionRepository;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use function is_subclass_of;

/** @template TUser of UserProviderInterface */
final readonly class AuthApiUserProvider implements UserProviderInterface
{
    public function __construct(
        private UsersApiClient $apiClient,
        private SubscriptionRepository $subscriptionRepository,
    ) {
    }

    public function supportsClass(string $class): bool
    {
        return is_subclass_of($class, ShopUserInterface::class);
    }

    /** @param ShopUserInterface $user */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (true === $user->getToken()->isExpired() && true === $user->getRefreshToken()->isExpired()) {
            throw new CredentialsExpiredException('Session Expired, please login again.');
        }

        $user = $this->apiClient->refreshUser($user);
        $subscription = $this->subscriptionRepository->findForUser($user->getId());
        $user->setSubscription($subscription);

        return $user;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->apiClient->loadUserByIdentifier($identifier);
    }
}
