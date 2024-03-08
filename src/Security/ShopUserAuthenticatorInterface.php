<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Contracts\ShopUserInterface;

interface ShopUserAuthenticatorInterface
{
    public function authenticateWithAuthCode(string $authCode): ShopUserInterface;
}
