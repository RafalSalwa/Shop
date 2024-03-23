<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\OAuth2UserConsent;
use App\Tests\Helpers\ProtectedPropertyHelper;
use DateTimeImmutable;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: OAuth2UserConsent::class)]
class OAuth2UserConsentTest extends TestCase
{
    private OAuth2UserConsent $userConsent;
    use ProtectedPropertyHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client('test', 'test', 'test');
        $this->userConsent = new OAuth2UserConsent(1, $this->client);
        $this->setProtectedProperty($this->userConsent, 'id', 1);
    }

    public function testGettersAndSetters(): void
    {
        $this->assertEquals(1, $this->userConsent->getId());

        $created = new DateTimeImmutable();
        $this->userConsent->setCreated($created);
        $this->assertEquals($created, $this->userConsent->getCreated());

        $expires = new DateTimeImmutable();
        $this->userConsent->setExpires($expires);
        $this->assertEquals($expires, $this->userConsent->getExpires());

        $scopes = ['email', 'id'];
        $this->userConsent->setScopes($scopes);
        $this->assertEquals($scopes, $this->userConsent->getScopes());

        $ipAddress = '192.168.1.1';
        $this->userConsent->setIpAddress($ipAddress);
        $this->assertEquals($ipAddress, $this->userConsent->getIpAddress());

        $this->userConsent->setClient($this->client);
        $this->assertEquals($this->client, $this->userConsent->getClient());
    }
}
