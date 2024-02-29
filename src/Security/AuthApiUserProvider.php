<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\UsersApiClient;
use App\Entity\ShopUserInterface;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use function is_subclass_of;

/** @template TUser of UserProviderInterface */
final readonly class AuthApiUserProvider implements UserProviderInterface
{
    public function __construct(private UsersApiClient $apiClient)
    {}

    public function supportsClass(string $class): bool
    {
        return is_subclass_of($class, ShopUserInterface::class);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (false === $user->getToken()->isExpired() && false === $user->getRefreshToken()->isExpired()) {
            throw new CredentialsExpiredException('Session Expired, please login again.');
        }

        return $this->apiClient->refreshUser($user);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->apiClient->loadUserByIdentifier($identifier);
    }
}
