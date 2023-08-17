<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: AddressRepository::class)]
#[Table(name: 'address')]
class Address
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'address_id', type: Types::INTEGER, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'address_addressId_seq', allocationSize: 1, initialValue: 1)]
    private $id;
    #[Assert\NotBlank]
    #[Column(name: 'first_name', type: Types::STRING, length: 40)]
    private $firstName;
    #[Assert\NotBlank]
    #[Column(name: 'last_name', type: Types::STRING, length: 40)]
    private $lastName;
    #[Assert\NotBlank]
    #[Column(name: 'address_line_1', type: Types::STRING, length: 40)]
    private $addressLine1;

    #[Assert\NotBlank]
    #[Column(name: 'address_line_2', type: Types::STRING, length: 40, nullable: true)]
    private $addressLine2;
    #[Assert\NotBlank]
    #[Column(name: 'city', type: Types::STRING, length: 40)]
    private $city;
    #[Assert\NotBlank]
    #[Column(name: 'state', type: Types::STRING, length: 40)]
    private $state;
    #[Assert\NotBlank]
    #[Column(name: 'postal_code', type: Types::STRING, length: 40)]
    private $postalCode;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'addressess')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    private UserInterface $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Address
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return Address
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return Address
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * @param mixed $addressLine1
     * @return Address
     */
    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * @param mixed $addressLine2
     * @return Address
     */
    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     * @return Address
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     * @return Address
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): Address
    {
        $this->user = $user;
        return $this;
    }


}