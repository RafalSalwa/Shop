<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\OAuth2UserConsent;
use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use App\Enum\SubscriptionTier;
use App\Model\User;
use App\Tests\Helpers\TokenTestHelperTrait;
use App\ValueObject\EmailAddress;
use App\ValueObject\Token;
use Doctrine\Common\Collections\Collection;
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
    use TokenTestHelperTrait;
    private string $token;

    public function testUserInitialization(): void
    {
        $email = 'test@example.com';
        $user = new User($email);
        $token = new Token($this->generateTokenString());
        $user->setToken($token);
        $user->setRefreshToken($token);

        $anotherUser = new User($email);
        $token = new Token($this->generateTokenString());
        $user->setToken($token);
        $user->setRefreshToken($token);

        $this->assertInstanceOf(ShopUserInterface::class, $user);
        $this->assertInstanceOf(Token::class, $user->getToken());
        $this->assertInstanceOf(Token::class, $user->getRefreshToken());

        $this->assertNotNull($user->getUserIdentifier());
        $this->assertSame($email, $user->getEmail());
        $this->assertCount(0, $user->getConsents());
        $this->assertSame(['ROLE_USER'], $user->getRoles());

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
        $user = new User('test@example.com');
        $token = new Token($this->generateTokenString());
        $user->setToken($token);
        $user->setRefreshToken($token);

        $oAuth2UserConsent = new OAuth2UserConsent($user->getId(), $client);

        $user->addConsent($oAuth2UserConsent);

        $consents = $user->getConsents();
        $this->assertNotNull($consents);
        $this->assertInstanceOf(Collection::class, $consents);
        $this->assertCount(1, $consents);
        $this->assertInstanceOf(OAuth2UserConsent::class, $consents[0]);
    }
}
