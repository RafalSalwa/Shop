<?php

declare(strict_types=1);

namespace App\Model\GRPC;

use App\Model\TokenPair;

final class UserResponse
{
    public function __construct(
        private string $email,
        private string $password,
        private string $vCode,
        private bool $isVerified,
        private ?TokenPair $tokenPair = null,
    ) {
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

    public function getTokenPair(): ?TokenPair
    {
        return $this->tokenPair;
    }

    public function withIsVerified(bool $isVerified): self
    {
        $copy = clone $this;
        $copy->isVerified = $isVerified;

        return $copy;
    }

    public function withTokenPair(TokenPair $tokenPair): self
    {
        $copy = clone $this;
        $copy->tokenPair = $tokenPair;

        return $copy;
    }
}
