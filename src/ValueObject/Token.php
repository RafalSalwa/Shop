<?php

declare(strict_types=1);

namespace App\ValueObject;

use DateTime;
use DateTimeImmutable;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;

final readonly class Token
{
    private UnencryptedToken $parsedToken;

    /** @throws InvalidTokenStructure */
    public function __construct(private string $token)
    {
        $parser = new Parser(new JoseEncoder());
        $this->parsedToken = $parser->parse($this->token);
    }

    public function value(): string
    {
        return $this->token;
    }

    public function isExpired(): bool
    {
        return $this->parsedToken->isExpired(new DateTime());
    }

    public function getSub(): string
    {
        return $this->parsedToken->claims()->get("sub");
    }

    public function getExp(): DateTimeImmutable
    {
        return $this->parsedToken->claims()->get("exp");
    }
}
