<?php

namespace App\Tests\Unit\Model;

namespace App\Tests\Model;

use App\Entity\OAuth2UserConsent;
use App\Model\User;
use App\ValueObject\EmailAddress;
use App\ValueObject\Token;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserInitialization(): void
    {
        $email = 'test@example.com';
        $token = 'test_token';
        $refreshToken = 'test_refresh_token';

        $user = new User(1, $email, $token, $refreshToken);

        $this->assertInstanceOf(User::class, $user);
        $this->assertInstanceOf(EmailAddress::class, $user->getEmail());
        $this->assertSame($email, $user->getEmail());
        $this->assertInstanceOf(Token::class, $user->getToken());
        $this->assertSame($token, $user->getToken()->value());
        $this->assertInstanceOf(Token::class, $user->getRefreshToken());
        $this->assertSame($refreshToken, $user->getRefreshToken()->value());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testAddConsent(): void
    {
        $user = new User(1, 'test@example.com', 'test_token', 'test_refresh_token');
        $consent = new OAuth2UserConsent();

        $user->addConsent($consent);

        $consents = $user->getConsents();
        $this->assertNotNull($consents);
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $consents);
        $this->assertCount(1, $consents);
        $this->assertInstanceOf(OAuth2UserConsent::class, $consents[0]);
    }
}
