<?php

declare(strict_types=1);

namespace App\Tests\Unit\ValueObject;

use App\Tests\Helpers\TokenTestHelperTrait;
use App\ValueObject\Token;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: Token::class)]
final class TokenTest extends TestCase
{
    use TokenTestHelperTrait;

    private string $tokenString;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tokenString = $this->generateTokenString();
    }

    public function testTokenConstruction(): void
    {
        $token = new Token($this->tokenString);

        $this->assertInstanceOf(Token::class, $token);
        $this->assertSame($this->tokenString, $token->value());
    }

    public function testIsExpired(): void
    {
        $expiredToken = new Token($this->tokenString);

        $this->assertFalse($expiredToken->isExpired());
    }

    public function testGetSub(): void
    {
        $userId = 'component1';
        $token = new Token($this->tokenString);

        $this->assertSame($userId, $token->getSub());
    }

    public function testToString(): void
    {
        $token = new Token($this->tokenString);

        $this->assertSame($this->tokenString, (string)$token);
    }
}
