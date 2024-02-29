<?php

declare(strict_types=1);

namespace App\Client;

use App\Entity\ShopUserInterface;
use App\ValueObject\Token;

interface ShopUserProviderInterface
{
    public function refreshUser(ShopUserInterface $user): ShopUserInterface;

    public function loadUserByIdentifier(string $identifier): ShopUserInterface;

    public function loadUserByToken(Token $token): ShopUserInterface;
}
