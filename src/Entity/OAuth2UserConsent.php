<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OAuth2UserConsentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use League\Bundle\OAuth2ServerBundle\Model\Client;

#[ORM\Entity(repositoryClass: OAuth2UserConsentRepository::class)]
class OAuth2UserConsent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    #[ORM\Column]
    private DateTimeImmutable|null $created = null;

    #[ORM\Column(nullable: true)]
    private DateTimeImmutable|null $expires = null;

    /**
     * @var list<string>
     * $scopes = ['email', 'id']
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $scopes = ['email', 'id'];

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $ipAddress = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(referencedColumnName: 'identifier', nullable: false)]
    private Client|null $client = null;

    #[ORM\Column]
    private int $userId;

    public function __construct(int $userId, Client $client)
    {
        $this->userId = $userId;
        $this->client = $client;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getCreated(): DateTimeImmutable|null
    {
        return $this->created;
    }

    public function setCreated(DateTimeImmutable $created): void
    {
        $this->created = $created;
    }

    public function getExpires(): DateTimeImmutable|null
    {
        return $this->expires;
    }

    public function setExpires(DateTimeImmutable|null $expires): void
    {
        $this->expires = $expires;
    }

    /** @return array<string> */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /** @param array<string> $scopes */
    public function setScopes(array $scopes): void
    {
        $this->scopes = $scopes;
    }

    public function getIpAddress(): string|null
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string|null $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    public function getClient(): Client|null
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
}
