<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\UsersApiClient;
use App\Model\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use function dd;
use function is_subclass_of;

/** @template TUser of PasswordUpgraderInterface */
final readonly class AuthApiUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private UsersApiClient $usersApiClient,
    ) {}

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
            return $this->usersApiClient->byIdentifier($identifier);
    }

    public function upgradePassword(
        PasswordAuthenticatedUserInterface $passwordAuthenticatedUser,
        string $newHashedPassword,
    ): void {
        dd(__FUNCTION__, self::class);
        // TODO: Implement upgradePassword() method.
    }
}
