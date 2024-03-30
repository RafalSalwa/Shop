<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\GRPC;

use App\Model\GRPC\UserResponse;
use App\Model\TokenPair;
use App\Tests\Helpers\TokenTestHelperTrait;
use App\ValueObject\Token;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: UserResponse::class)]
#[UsesClass(className: TokenPair::class)]
#[UsesClass(className: Token::class)]
final class UserResponseTest extends TestCase
{
    use TokenTestHelperTrait;

    public function testGetters(): void
    {
        $email = 'test@example.com';
        $password = 'password123';
        $confirmationCode = '123456';
        $tokenPair = $this->getTokenPair();

        $userResponse = new UserResponse($email, $password, $confirmationCode, true);
        $userResponse = $userResponse->withTokenPair($tokenPair);

        $this->assertSame($email, $userResponse->getEmail());
        $this->assertSame($password, $userResponse->getPassword());
        $this->assertSame($confirmationCode, $userResponse->getConfirmationCode());
        $this->assertTrue($userResponse->isVerified());
        $this->assertInstanceOf(TokenPair::class, $userResponse->getTokenPair());
        $this->assertSame($tokenPair, $userResponse->getTokenPair());
    }

    public function testWithIsVerified(): void
    {
        $userResponse = new UserResponse('test@example.com', 'password123', '123456', true);
        $this->assertTrue($userResponse->isVerified());
        $newUserResponse = $userResponse->withIsVerified(false);

        $this->assertFalse($newUserResponse->isVerified());
    }

    public function testWithTokenPair(): void
    {
        $tokenPair = $this->getTokenPair();
        $userResponse = new UserResponse('test@example.com', 'password123', '123456', true);
        $newUserResponse = $userResponse->withTokenPair($tokenPair);

        $this->assertNull($userResponse->getTokenPair());
        $this->assertInstanceOf(TokenPair::class, $newUserResponse->getTokenPair());
        $this->assertSame($tokenPair, $newUserResponse->getTokenPair());
    }
}
