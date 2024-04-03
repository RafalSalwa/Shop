<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\OAuth2UserConsent;
use App\Tests\Helpers\ProtectedPropertyTrait;
use DateTimeImmutable;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: OAuth2UserConsent::class)]
final class OAuth2UserConsentTest extends TestCase
{
    use ProtectedPropertyTrait;

    private Client $client;

    private OAuth2UserConsent $userConsent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client('test', 'test', 'test');
        $this->userConsent = new OAuth2UserConsent(1, $this->client);
        $this->setProtectedProperty($this->userConsent, 'id', 1);
    }

    public function testGettersAndSetters(): void
    {
        $this->assertSame(1, $this->userConsent->getId());

        new DateTimeImmutable();
        $this->assertNotNull($this->userConsent->getCreated());

        $dateTimeImmutable = new DateTimeImmutable();
        $this->userConsent->setExpires($dateTimeImmutable);
        $this->assertSame($dateTimeImmutable, $this->userConsent->getExpires());

        $scopes = ['email', 'id'];
        $this->userConsent->setScopes($scopes);
        $this->assertSame($scopes, $this->userConsent->getScopes());

        $ipAddress = '192.168.1.1';
        $this->userConsent->setIpAddress($ipAddress);
        $this->assertSame($ipAddress, $this->userConsent->getIpAddress());

        $this->userConsent->setClient($this->client);
        $this->assertSame($this->client, $this->userConsent->getClient());
    }
}
