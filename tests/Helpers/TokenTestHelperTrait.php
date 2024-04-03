<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Model\TokenPair;
use App\ValueObject\Token;
use DateTimeImmutable;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;

trait TokenTestHelperTrait
{
    protected function getToken(): Token
    {
        return new Token($this->generateTokenString());
    }

    protected function getTokenPair(): TokenPair
    {
        $token = new Token($this->generateTokenString());

        return new TokenPair($token, $token);
    }

    private function generateTokenString(): string
    {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $sha256    = new Sha256();
        $signingKey = InMemory::base64Encoded('hiG8DlOKvtih6AxlZn5XKImZ06yu8I3mkOzaJrEuW8yAv8Jnkw330uMt8AEqQ5LB');
        $dateTimeImmutable   = new DateTimeImmutable();
        $token = $tokenBuilder
            ->issuedBy('http://example.com')
            ->permittedFor('http://example.org')
            ->relatedTo('component1')
            ->identifiedBy('4f1g23a12aa')
            ->issuedAt($dateTimeImmutable)
            ->canOnlyBeUsedAfter($dateTimeImmutable->modify('+1 minute'))
            ->expiresAt($dateTimeImmutable->modify('+1 hour'))
            ->withClaim('uid', 1)
            ->withHeader('foo', 'bar')
            ->getToken($sha256, $signingKey);

        return $token->toString();
    }
}
