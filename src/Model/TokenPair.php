<?php

declare(strict_types=1);

namespace App\Model;

use App\ValueObject\Token;
use JsonException;
use function json_decode;
use const JSON_THROW_ON_ERROR;

final class TokenPair
{
    public function __construct(protected Token $token, protected Token $refreshToken)
    {
    }

    /** @throws JsonException */
    public static function fromJson(string $jsonResponse): self
    {
        $arrTokens = json_decode($jsonResponse, true, 4, JSON_THROW_ON_ERROR);
        $accessToken = new Token($arrTokens['user']['token']);
        $refreshToken = new Token($arrTokens['user']['refresh_token']);

        return new self($accessToken, $refreshToken);
    }

    public function getToken(): Token
    {
        return $this->token;
    }

    public function getRefreshToken(): Token
    {
        return $this->refreshToken;
    }
}
