<?php

namespace App\Entity;

class SignInUserInput
{
    protected string $username = '';
    protected string $password = '';

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): SignInUserInput
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): SignInUserInput
    {
        $this->password = $password;
        return $this;
    }


}