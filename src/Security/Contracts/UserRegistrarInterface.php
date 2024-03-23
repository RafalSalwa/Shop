<?php

declare(strict_types=1);

namespace App\Security\Contracts;

interface UserRegistrarInterface
{
    public function register(string $email, string $password): void;

    public function sendVerificationCode(string $email): void;

    public function confirm(string $verificationCode): void;
}
