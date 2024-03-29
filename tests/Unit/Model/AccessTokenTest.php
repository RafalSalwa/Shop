<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Model\AccessToken;
use DateTimeImmutable;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: AccessToken::class)]
final class AccessTokenTest extends TestCase
{
    public function testConvert(): void
    {
        // Mock necessary dependencies
        $clientMock = $this->createMock(ClientEntityInterface::class);
        $clientMock->method('getIdentifier')->willReturn('test_client_id');

        $scopeMock1 = $this->createMock(ScopeEntityInterface::class);
        $scopeMock1->method('getIdentifier')->willReturn('scope1');

        $scopeMock2 = $this->createMock(ScopeEntityInterface::class);
        $scopeMock2->method('getIdentifier')->willReturn('scope2');
        // Create AccessToken instance
        $keyString = file_get_contents(__DIR__ . '/../../../config/jwt/private.key');
        $accessToken = new AccessToken($keyString, 'rsinterview');
        $accessToken->setClient($clientMock);
        // Set necessary properties
        $accessToken->setIdentifier('test_token_id');
        $accessToken->setExpiryDateTime(new DateTimeImmutable('2024-03-22'));
        $accessToken->setUserIdentifier(123);

        $this->assertNotNull($accessToken);
        $this->assertInstanceOf(AccessTokenEntityInterface::class, $accessToken);
    }
}
