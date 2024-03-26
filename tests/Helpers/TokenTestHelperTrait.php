<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Model\TokenPair;
use App\ValueObject\Token;
use DateTimeImmutable;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;

trait TokenTestHelperTrait
{
    protected function getToken(): string
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
        $key = InMemory::base64Encoded(
            'hiG8DlOKvtih6AxlZn5XKImZ06yu8I3mkOzaJrEuW8yAv8Jnkw330uMt8AEqQ5LB'
        );

        $token = (new JwtFacade())->issue(
            new Sha256(),
            $key,
            static fn (
                Builder $builder,
                DateTimeImmutable $issuedAt
            ): Builder => $builder
                ->issuedBy('https://api.my-awesome-app.io')
                ->permittedFor('https://client-app.io')
                ->relatedTo('1')
                ->expiresAt($issuedAt->modify('+10 minutes'))
        );

        return $token->toString();
    }
}
