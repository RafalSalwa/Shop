<?php

declare(strict_types=1);

namespace App\Client\Contracts;

use App\Model\TokenPair;

interface AuthClientInterface
{
    public function signIn(string $email, string $password): TokenPair;

    public function signUp(string $email, string $password): void;

    public function confirmAccount(string $verificationCode): void;

    public function getVerificationCode(string $email): ?string;

    /** @return array<string, array<string, array<array-key, mixed>>> */
    public function getResponses(): array;
}
