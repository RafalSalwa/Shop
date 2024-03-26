<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OAuth2UserConsentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use DateInterval;

#[ORM\Entity(repositoryClass: OAuth2UserConsentRepository::class)]
class OAuth2UserConsent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(nullable: false)]
    private DateTimeImmutable $created;

    #[ORM\Column(nullable: false)]
    private DateTimeImmutable $expires;

    /**
     * @var list<string>
     * $scopes = ['email', 'id']
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: false)]
    private array $scopes = ['email', 'id'];

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $ipAddress = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(referencedColumnName: 'identifier', nullable: false)]
    private Client $client;

    #[ORM\Column]
    private int $userId;

    public function __construct(int $userId, Client $client)
    {
        $this->userId = $userId;
        $this->client = $client;

        $dateTimeImmutable = new DateTimeImmutable();
        $this->created = $dateTimeImmutable;
        $this->expires = $dateTimeImmutable->add(new DateInterval('P30D'));;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCreated(): DateTimeImmutable|null
    {
        return $this->created;
    }

    public function getExpires(): DateTimeImmutable|null
    {
        return $this->expires;
    }

    public function setExpires(DateTimeImmutable $expires): void
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
