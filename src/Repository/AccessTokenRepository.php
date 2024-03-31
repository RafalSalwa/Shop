<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\AccessToken as AccessTokenEntity;
use League\Bundle\OAuth2ServerBundle\Repository\AccessTokenRepository as BaseAccessTokenRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

final readonly class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function __construct(
        private BaseAccessTokenRepository $baseAccessTokenRepository,
        private string $privateJWTKey,
        private string $privateJWTKeyPassphrase,
    ) {}

    /**
     * @param array<ScopeEntityInterface> $scopes
     * @param mixed                       $userIdentifier
     */
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null,
    ): AccessTokenEntity {
        $accessToken = new AccessTokenEntity($this->privateJWTKey, $this->privateJWTKeyPassphrase);
        $accessToken->setClient($clientEntity);
        $accessToken->setUserIdentifier($userIdentifier);

        return $accessToken;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        $this->baseAccessTokenRepository->persistNewAccessToken($accessTokenEntity);
    }

    /** @param string $tokenId */
    public function revokeAccessToken($tokenId): void
    {
        $this->baseAccessTokenRepository->revokeAccessToken($tokenId);
    }

    /** @param string $tokenId */
    public function isAccessTokenRevoked($tokenId): bool
    {
        return $this->baseAccessTokenRepository->isAccessTokenRevoked($tokenId);
    }
}
