<?php

namespace App\Security\Registration;

interface UserActivatorInterface {

    public function activate(string $verificationCode):bool;
}
