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

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private array $scopes = [];

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $ipAddress = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(referencedColumnName: 'identifier', nullable: false)]
    private Client|null $client = null;

    #[ORM\Column]
    private int $userId;

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getUser(): User|null
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreated(): DateTimeImmutable|null
    {
        return $this->created;
    }

    public function setCreated(DateTimeImmutable $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getExpires(): DateTimeImmutable|null
    {
        return $this->expires;
    }

    public function setExpires(DateTimeImmutable|null $expires): self
    {
        $this->expires = $expires;

        return $this;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function setScopes(array|null $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }

    public function getIpAddress(): string|null
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string|null $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getClient(): Client|null
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
