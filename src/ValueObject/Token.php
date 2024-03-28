<?php

declare(strict_types=1);

namespace App\ValueObject;

use DateTime;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;
use Stringable;

use function assert;
use function is_string;

final readonly class Token implements Stringable
{
    private UnencryptedToken $parsedToken;

    /** @throws InvalidTokenStructure */
    public function __construct(private string $token)
    {
        assert('' !== $this->token);
        $parsedToken = (new Parser(new JoseEncoder()))->parse($this->token);

        assert($parsedToken instanceof UnencryptedToken);
        $this->parsedToken = $parsedToken;
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
        if (true === $this->parsedToken->claims()->has('sub')) {
            $sub = $this->parsedToken->claims()->get('sub');
            assert(is_string($sub));

            return $sub;
        }

        throw InvalidTokenStructure::missingClaimsPart();
    }

    public function __toString(): string
    {
        return $this->token;
    }
}
