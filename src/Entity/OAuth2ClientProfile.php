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

    #[ORM\Column(length: 255)]
    private string|null $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private string|null $description = null;

    public function getId(): int|null
    {
        return $this->id;
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

    public function getName(): string|null
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string|null
    {
        return $this->description;
    }

    public function setDescription(string|null $description): self
    {
        $this->description = $description;

        return $this;
    }
}
