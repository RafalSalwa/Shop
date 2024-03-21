<?php

declare(strict_types=1);

namespace App\Model;

/** @psalm-api */
final class SignInUserInput
{
    protected string $email = '';

    protected string $password = '';

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
