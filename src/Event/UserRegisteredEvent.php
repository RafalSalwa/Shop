<?php

declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class UserRegisteredEvent extends Event
{
    public function __construct(protected readonly string $email, protected readonly string $confirmationCode)
    {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getConfirmationCode(): string
    {
        return $this->confirmationCode;
    }
}
