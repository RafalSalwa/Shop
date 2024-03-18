<?php

declare(strict_types=1);

namespace App\Security\Registration;

interface UserActivatorInterface
{
    public function activate(string $verificationCode): bool;
}
