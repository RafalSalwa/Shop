<?php

declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class UserRegisteredEvent extends Event
{
    public function __construct(protected string $email)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
