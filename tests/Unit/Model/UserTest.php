<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\OAuth2UserConsent;
use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;
use App\Exception\AuthException;
use App\Model\User;
use App\Tests\Helpers\ProtectedPropertyHelper;
use App\Tests\Helpers\TokenTestHelperTrait;
use App\ValueObject\EmailAddress;
use App\ValueObject\Token;
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
final class UserTest extends TestCase
{
    use ProtectedPropertyHelper;
    use TokenTestHelperTrait;

    private User $goodUser;

    private User $badUser;

    private Token $token;

    protected function setUp(): void
    {
        $token = new Token($this->generateTokenString());
        $this->token = $token;

        $user = new User('test@example.com');
        $user->setToken($token);
        $user->setRefreshToken($token);

        $this->goodUser = $user;

        $user = new User('test2@example.com');
        $this->badUser = $user;
    }

    public function testUserInitialization(): void
    {
        $goodUser = $this->goodUser;

        $badUser = $this->badUser;
        $anotherUser = new User('test@example.com');

        $this->assertInstanceOf(ShopUserInterface::class, $goodUser);
        $this->assertInstanceOf(Token::class, $goodUser->getToken());
        $this->assertInstanceOf(Token::class, $goodUser->getRefreshToken());

        $this->assertSame($this->token->value(), $goodUser->getUserIdentifier());
        $this->assertSame('test@example.com', $goodUser->getEmail());
        $this->assertCount(0, $goodUser->getConsents());
        $this->assertSame(['ROLE_USER'], $goodUser->getRoles());

        $subscription = new Subscription($goodUser->getId(), new SubscriptionPlan());
        $goodUser->setSubscription($subscription);
        $this->assertInstanceOf(Subscription::class, $goodUser->getSubscription());
        $this->assertSame($goodUser->getSubscription(), $subscription);
        $this->assertTrue($goodUser->isEqualTo($anotherUser));
        $this->assertFalse($goodUser->isEqualTo($badUser));
        $goodUser->eraseCredentials();
    }

    public function testGettersSetters(): void
    {
        $badUser = $this->badUser;
        $this->expectException(AuthException::class);
        $badUser->getUserIdentifier();
    }

    public function testAddConsent(): void
    {
        $client = new Client('test', 'test', 'test');
        $user = new User('test@example.com');
        $token = new Token($this->generateTokenString());
        $user->setToken($token);
        $user->setRefreshToken($token);

        $oAuth2UserConsent = new OAuth2UserConsent($user->getId(), $client);

        $user->addConsent($oAuth2UserConsent);

        $consents = $user->getConsents();
        $this->assertCount(1, $consents);
        $this->assertInstanceOf(OAuth2UserConsent::class, $consents[0]);
    }
}
