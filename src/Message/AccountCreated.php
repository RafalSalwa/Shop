<?php

declare(strict_types=1);

namespace App\Message;

final class AccountCreated
{
    public function __construct(private string $userId)
    {
    }

    public function getContent(): string
    {
        return $this->userId;
    }
}