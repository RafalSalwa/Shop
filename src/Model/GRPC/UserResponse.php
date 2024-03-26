<?php

declare(strict_types=1);

namespace App\Model\GRPC;

use App\Model\TokenPair;

final class UserResponse
{
    private ?TokenPair $tokenPair = null;

    public function __construct(
        private readonly string $email,
        private readonly string $password,
        private readonly string $confirmationCode,
        private bool $isVerified,
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

    public function getConfirmationCode(): string
    {
        return $this->confirmationCode;
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
