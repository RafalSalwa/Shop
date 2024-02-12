<?php

namespace App\Model;

class ApiTokenPair {

    protected string $token = '';
    protected string $refreshToken='';

    public function __construct(string $token, string $refreshToken)
    {
        $this->token = $token;
        $this->refreshToken = $refreshToken;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

}
