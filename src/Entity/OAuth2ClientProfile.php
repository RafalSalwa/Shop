<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OAuth2ClientProfileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use League\Bundle\OAuth2ServerBundle\Model\Client;

#[ORM\Entity(repositoryClass: OAuth2ClientProfileRepository::class)]
class OAuth2ClientProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(referencedColumnName: 'identifier', nullable: false)]
    private Client $client;

    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct(Client $client, string $name)
    {
        $this->name = $name;
        $this->client = $client;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getClient(): Client|null
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string|null $description): void
    {
        $this->description = $description;
    }
}
