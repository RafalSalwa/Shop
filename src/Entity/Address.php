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

    #[Column(name: 'phone_no', type: Types::STRING, length: 12, nullable: true)]
    private string $phoneNo;

    #[Assert\NotBlank]
    #[Column(name: 'city', type: Types::STRING, length: 40)]
    private string $city;

    #[Assert\NotBlank]
    #[Column(name: 'state', type: Types::STRING, length: 40)]
    private string $state;

    #[Assert\NotBlank]
    #[Column(name: 'postal_code', type: Types::STRING, length: 40)]
    private string $postalCode;

    #[Column(name: 'country', type: Types::STRING, length: 40)]
    private string $country;

    #[Column(name: 'user_id', type: Types::INTEGER)]
    private int $userId;

    #[Column(name: 'isDefault', type: Types::BOOLEAN, nullable: false, options: ['default'=> 0])]
    private bool $isDefault = false;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getAddressLine1(): string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(string $addressLine1): void
    {
        $this->addressLine1 = $addressLine1;
    }

    public function getAddressLine2(): string|null
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(string $addressLine2): void
    {
        $this->addressLine2 = $addressLine2;
    }

    public function getPhoneNo(): string
    {
        return $this->phoneNo;
    }

    public function setPhoneNo(string $phoneNo): void
    {
        $this->phoneNo = $phoneNo;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setDefault(bool $default): void
    {
        $this->isDefault = $default;
    }


    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
