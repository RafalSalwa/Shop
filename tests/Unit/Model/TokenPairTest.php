<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Model\TokenPair;
use App\Tests\Helpers\TokenTestHelperTrait;
use App\ValueObject\Token;
use JsonException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

use function json_encode;

#[CoversClass(className: TokenPair::class)]
#[UsesClass(className: Token::class)]
final class TokenPairTest extends TestCase
{
    use TokenTestHelperTrait;

    private string $token;

    protected function setUp(): void
    {
        $this->token = $this->generateTokenString();
    }

    public function testGetToken(): void
    {
        $token = new Token($this->token);
        $tokenPair = new TokenPair($token, $token);
        $this->assertInstanceOf(TokenPair::class, $tokenPair);
        $this->assertInstanceOf(Token::class, $tokenPair->getToken());
        $this->assertInstanceOf(Token::class, $tokenPair->getRefreshToken());
    }

    public function testMappingFromJson(): void
    {
        $arrJson = [];
        $arrJson['user'] = [
            'token' => $this->token,
            'refresh_token' => $this->token,
        ];
        $tokenPair = TokenPair::fromJson(json_encode($arrJson));

        $this->assertInstanceOf(TokenPair::class, $tokenPair);

        $this->expectException(JsonException::class);
        TokenPair::fromJson('wrong json string');
    }
}
