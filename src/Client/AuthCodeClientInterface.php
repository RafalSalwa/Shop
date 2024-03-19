<?php

declare(strict_types=1);

namespace App\Client;

use App\Entity\Contracts\ShopUserInterface;
use App\Model\TokenPair;

interface AuthCodeClientInterface
{
    public function getByVerificationCode(string $verificationCode): ShopUserInterface;

    public function signInByCode(string $email, string $verificationCode): TokenPair;
}
