<?php

declare(strict_types=1);

namespace App\Client;

use App\Entity\ShopUserInterface;
use App\Model\TokenPair;

interface AuthClientInterface
{
    public function signIn(string $email, string $password): TokenPair;

    public function signUp(string $email, string $password): bool;

    public function confirmAccount(string $verificationCode): void;

    public function getByVerificationCode(string $verificationCode): ShopUserInterface;

    public function signInByCode(string $email, string $verificationCode): TokenPair;
}
