<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Model\TokenPair;
use App\ValueObject\Token;
use DateTimeImmutable;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: TokenPair::class)]
#[UsesClass(className: Token::class)]
final class TokenPairTest extends TestCase
{
    private string $token;

    protected function setUp(): void
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
        $this->token = $token->toString();
    }

    public function testGetToken()
    {
        $token = new Token($this->token);
        $tokenPair = new TokenPair($token, $token);
        $this->assertInstanceOf(TokenPair::class, $tokenPair);
        $this->assertInstanceOf(Token::class, $tokenPair->getToken());
        $this->assertInstanceOf(Token::class, $tokenPair->getRefreshToken());
    }

    public function testMappingFromJson()
    {
        $arr['user'] = [
            'token'=>$this->token,
            'refresh_token'=>$this->token,
        ];
        $tokenPair = TokenPair::fromJson(json_encode($arr));
        $this->assertInstanceOf(TokenPair::class, $tokenPair);
    }
}
