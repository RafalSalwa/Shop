<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: AddressRepository::class)]
#[Table(name: 'address', schema: 'interview')]
class Address
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'address_id', type: Types::INTEGER, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'address_addressID_seq', allocationSize: 1, initialValue: 1)]
    private int $id;

    #[Assert\NotBlank]
    #[Column(name: 'first_name', type: Types::STRING, length: 40)]
    private string $firstName;

    #[Assert\NotBlank]
    #[Column(name: 'last_name', type: Types::STRING, length: 40)]
    private string $lastName;

    #[Assert\NotBlank]
    #[Column(name: 'address_line_1', type: Types::STRING, length: 40)]
    private string $addressLine1;

    #[Column(name: 'address_line_2', type: Types::STRING, length: 40, nullable: true)]
    private string|null $addressLine2 = null;

    #[Assert\NotBlank]
    #[Column(name: 'city', type: Types::STRING, length: 40)]
    private string $city;

    #[Assert\NotBlank]
    #[Column(name: 'state', type: Types::STRING, length: 40)]
    private string $state;

    #[Assert\NotBlank]
    #[Column(name: 'postal_code', type: Types::STRING, length: 40)]
    private string $postalCode;

    #[Column(name: 'user_id', type: Types::INTEGER)]
    private int $userId;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddressLine1(): string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    public function getAddressLine2(): string|null
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(string $addressLine2): self
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
