<?php

namespace App\Model\GRPC;

final readonly class UserResponse {

    public function __construct(
        private string $email,
        private string $password,
        private string $vCode,
        private bool $isVerified,
    )
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getVCode(): string
    {
        return $this->vCode;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }


}
