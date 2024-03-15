<?php

declare(strict_types=1);

namespace App\Model\GRPC;

final class VerificationCodeRequest
{
    private string $verificationCode;

    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }

    public function setVerificationCode(string $verificationCode): void
    {
        $this->verificationCode = $verificationCode;
    }
}
