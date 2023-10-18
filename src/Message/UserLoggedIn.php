<?php

declare(strict_types=1);

namespace App\Message;

final readonly class UserLoggedIn
{
    public function __construct(private string $userId)
    {
    }

    public function getContent(): string
    {
        return $this->userId;
    }
}
