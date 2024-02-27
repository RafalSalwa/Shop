<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class UserVerificationCodeRequestEvent extends Event {

    /**
     * @param string $email
     */
    public function __construct(string $email)
    {
    }
}
