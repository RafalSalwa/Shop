<?php

declare(strict_types=1);

namespace App\Client;

use App\Model\TokenPair;

interface AuthClientInterface
{
    public function signIn(string $email, string $password): TokenPair;

    public function signUp(string $email, string $password): void;

    public function confirmAccount(string $verificationCode): void;
}
