<?php

declare(strict_types=1);

namespace App\Client;

use App\Model\ApiTokenPair;
use App\Model\User;

interface AuthClientInterface
{
    public function signIn(string $email, string $password): ApiTokenPair;

    public function signUp(string $email, string $password): bool;

    public function confirmAccount(string $verificationCode): void;

    public function getByVerificationCode(string $verificationCode): User;
}
