<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class UserConfirmedEvent extends Event
{

    public function __construct(private readonly string $verificationCode)
    {
    }

    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }

}
