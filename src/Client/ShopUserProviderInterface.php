<?php

declare(strict_types=1);

namespace App\Client;

use App\Entity\Contracts\ShopUserInterface;
use App\ValueObject\Token;
use Symfony\Component\Security\Core\User\UserInterface;

interface ShopUserProviderInterface
{
    public function refreshUser(ShopUserInterface $user): ShopUserInterface;

    public function loadUserByIdentifier(string $identifier): UserInterface;

    public function loadUserByToken(Token $token): ShopUserInterface;
}
