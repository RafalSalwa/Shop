<?php

namespace App\Tests\Unit\Model;

namespace App\Tests\Model;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\OAuth2UserConsent;
use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;
use App\Model\User;
use App\ValueObject\EmailAddress;
use App\ValueObject\Token;
use DateTimeImmutable;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: User::class)]
#[UsesClass(className: ShopUserInterface::class)]
#[UsesClass(className: Token::class)]
#[UsesClass(className: EmailAddress::class)]
#[UsesClass(className: Client::class)]
#[UsesClass(className: OAuth2UserConsent::class)]
#[UsesClass(className: Subscription::class)]
#[UsesClass(className: SubscriptionPlan::class)]
#[UsesClass(className: SubscriptionTier::class)]
class UserTest extends TestCase
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

    public function testUserInitialization(): void
    {
        $email = 'test@example.com';
        $user = new User(1, $email, $this->token, $this->token);
        $anotherUser = new User(2, $email, $this->token, $this->token);
        $this->assertInstanceOf(ShopUserInterface::class, $user);
        $this->assertInstanceOf(Token::class, $user->getToken());
        $this->assertInstanceOf(Token::class, $user->getRefreshToken());

        $this->assertNotNull($user->getUserIdentifier());
        $this->assertSame($email, $user->getEmail());
        $this->assertEquals(0, count($user->getConsents()));
        $this->assertEquals(['ROLE_USER'], $user->getRoles());

        $subscription = new Subscription($user->getId(), new SubscriptionPlan());
        $user->setSubscription($subscription);
        $this->assertInstanceOf(Subscription::class, $user->getSubscription());
        $this->assertSame($user->getSubscription(), $subscription);
        $this->assertTrue($user->isEqualTo($user));
        $this->assertFalse($user->isEqualTo($anotherUser));
        $user->eraseCredentials();
    }

    public function testAddConsent(): void
    {
        $client = new Client('test', 'test', 'test');
        $user = new User(1, 'test@example.com', $this->token, $this->token);
        $consent = new OAuth2UserConsent($user->getId(), $client);

        $user->addConsent($consent);

        $consents = $user->getConsents();
        $this->assertNotNull($consents);
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $consents);
        $this->assertCount(1, $consents);
        $this->assertInstanceOf(OAuth2UserConsent::class, $consents[0]);
    }
}
