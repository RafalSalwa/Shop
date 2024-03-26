<?php

declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class UserConfirmedEvent extends Event
{
    public function __construct(private readonly int $userId)
    {}

    public function getUserId(): int
    {
        return $this->userId;
    }
}
