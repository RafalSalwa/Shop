<?php

namespace App\Entity;

class SignUpUserInput
{
    protected string $email = '';
    protected string $password = '';
    protected string $passwordConfirm = '';

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): SignUpUserInput
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): SignUpUserInput
    {
        $this->password = $password;
        return $this;
    }

    public function getPasswordConfirm(): string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): SignUpUserInput
    {
        $this->passwordConfirm = $passwordConfirm;
        return $this;
    }
}
