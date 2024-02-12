<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface {

    public function __construct()
    {
    }

    public function refreshUser(UserInterface $user)
    {
        dd(__FUNCTION__, __CLASS__);
        // TODO: Implement refreshUser() method.
    }

    public function supportsClass(string $class)
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        dd(__FUNCTION__, __CLASS__, $identifier);
        // TODO: Implement loadUserByIdentifier() method.
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        dd(__FUNCTION__, __CLASS__);
        // TODO: Implement upgradePassword() method.
    }
}