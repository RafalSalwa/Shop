<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;
use Lcobucci\JWT\UnencryptedToken;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

final class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
    use EntityTrait;
    use TokenEntityTrait;

    public function __construct(string $privateJWTKey)
    {
        $cryptKey = new CryptKey($privateJWTKey, 'rsinterview');
        $this->setPrivateKey($cryptKey);
    }

    public function convert(): UnencryptedToken
    {
        $this->initJwtConfiguration();

        return $this->jwtConfiguration->builder()
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier())
            ->issuedAt(new DateTimeImmutable())
            ->canOnlyBeUsedAfter(new DateTimeImmutable())
            ->expiresAt($this->getExpiryDateTime())
            ->relatedTo((string)$this->getUserIdentifier())
            ->withClaim('scopes', $this->getScopes())
            ->withClaim('kid', '1')
            ->withClaim(
                'custom',
                ['foo' => 'bar'],
            )
            ->getToken($this->jwtConfiguration->signer(), $this->jwtConfiguration->signingKey());
    }
}
