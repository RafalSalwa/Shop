<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\OAuth2ClientProfile;
use App\Entity\OAuth2UserConsent;
use App\Model\User;
use App\Tests\Helpers\TokenTestHelperTrait;
use App\ValueObject\EmailAddress;
use App\ValueObject\Token;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: OAuth2ClientProfile::class)]
#[UsesClass(className: OAuth2UserConsent::class)]
#[UsesClass(className: User::class)]
#[UsesClass(className: EmailAddress::class)]
#[UsesClass(className: Token::class)]
class OAuth2ClientProfileTest extends TestCase
{
    public $client;
    private OAuth2ClientProfile $profile;

    use TokenTestHelperTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client('test', 'test', 'test');
        $user = new User(1, 'test@example.com');
        new OAuth2UserConsent($user->getId(), $this->client);

        $this->profile = new OAuth2ClientProfile($this->client, 'test');
    }

    public function testGettersAndSetters(): void
    {
        $client = $this->client;
        $profile = $this->profile;

        $profile->setClient($client);
        $this->assertEquals($client, $profile->getClient());

        $profile->setName('Test Name');
        $this->assertSame('Test Name', $profile->getName());

        $profile->setDescription('Test Description');
        $this->assertSame('Test Description', $profile->getDescription());

        // Test nullable properties
        $this->assertNull($profile->getId());
    }
}
