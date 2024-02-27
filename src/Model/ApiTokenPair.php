<?php

declare(strict_types=1);

namespace App\Model;

use \JsonException;

class ApiTokenPair
{
    public function __construct(protected string $token, protected string $refreshToken)
    {
    }

    /**
     * @throws JsonException
     */
    public static function fromJson(string $jsonResponse): self
    {
        $arrTokens = json_decode($jsonResponse, true, 4, \JSON_THROW_ON_ERROR);
        return new self($arrTokens['user']['token'], $arrTokens['user']['refresh_token']);
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
