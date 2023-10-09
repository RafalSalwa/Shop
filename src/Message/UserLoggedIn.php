<?php

declare(strict_types=1);

namespace App\Message;

final class UserLoggedIn
{
    public function __construct(private readonly string $userId)
    {
    }

    public function getContent(): string
    {
        return $this->userId;
    }
}