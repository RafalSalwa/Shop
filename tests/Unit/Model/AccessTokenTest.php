<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Model\AccessToken;
use DateTimeImmutable;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: AccessToken::class)]
class AccessTokenTest  extends TestCase
{
    public function testConvert(): void
    {
        // Mock necessary dependencies
        $clientMock = $this->createMock(ClientEntityInterface::class);
        $clientMock->method('getIdentifier')->willReturn('test_client_id');

        $scopes = [];
        $scopeMock1 = $this->createMock(ScopeEntityInterface::class);
        $scopeMock1->method('getIdentifier')->willReturn('scope1');
        $scopes[] = $scopeMock1;

        $scopeMock2 = $this->createMock(ScopeEntityInterface::class);
        $scopeMock2->method('getIdentifier')->willReturn('scope2');
        $scopes[] = $scopeMock2;
        // Create AccessToken instance
        $cryptKey = new CryptKey(__DIR__ . '/../../../config/jwt/private.key','rsinterview');
        $accessToken = new AccessToken("LS0tLS1CRUdJTiBSU0EgUFJJVkFURSBLRVktLS0tLQpNSUlCUEFJQkFBSkJBTzVIKytVM0xrWC91SlRvRHhWN01CUURXSTdGU0l0VXNjbGFFKzlaUUg5Q2VpOGIxcUVmCnJxR0hSVDVWUis4c3UxVWtCUVpZTER3MnN3RTVWbjg5c0ZVQ0F3RUFBUUpCQUw4ZjRBMUlDSWEvQ2ZmdWR3TGMKNzRCdCtwOXg0TEZaZXMwdHdtV3Vha3hub3NaV0w4eVpSTUJpRmI4a25VL0hwb3piTnNxMmN1ZU9wKzVWdGRXNApiTlVDSVFENm9JdWxqcHdrZTFGY1VPaldnaXRQSjNnbFBma3NHVFBhdFYwYnJJVVI5d0loQVBOanJ1enB4ckhsCkUxRmJxeGtUNFZ5bWhCOU1HazU0Wk1jWnVjSmZOcjBUQWlFQWhML3UxOVZPdlVBWVd6Wjc3Y3JxMTdWSFBTcXoKUlhsZjd2TnJpdEg1ZGdjQ0lRRHR5QmFPdUxuNDlIOFIvZ2ZEZ1V1cjg3YWl5UHZ1YStxeEpXMzQrb0tFNXdJZwpQbG1KYXZsbW9jUG4rTkVRdGhLcTZuZFVYRGpXTTlTbktQQTVlUDZSUEs0PQotLS0tLUVORCBSU0EgUFJJVkFURSBLRVktLS0tLQ==", $scopes);
        $accessToken->setPrivateKey($cryptKey);
        $accessToken->setClient($clientMock);
        // Set necessary properties
        $accessToken->setIdentifier('test_token_id');
        $accessToken->setExpiryDateTime(new DateTimeImmutable('2024-03-22'));
        $accessToken->setUserIdentifier(123);

        // Call the method to test
        $result = $accessToken->convert();

        // Assertions
        $this->assertInstanceOf(\Lcobucci\JWT\Token::class, $result);
        // You may add more specific assertions based on your implementation
    }
}
