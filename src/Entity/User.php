<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\SequenceGenerator;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: 'user')]
#[HasLifecycleCallbacks]
class User implements \JsonSerializable
{
    #[Id]
    #[GeneratedValue]
    #[Column(name:"id",type: Types::INTEGER, unique: true)]
    private int $id;

    #[Column(name:"username", type: Types::STRING, length: 180)]
    private string $username;
    #[Column(name:"password", type: Types::STRING, length: 255)]
    private string $password;
    #[Column(name:"first_name", type: Types::STRING, length: 255)]
    private ?string $firstname = null;
    #[Column(name:"last_name", type: Types::STRING, length: 255)]
    private ?string $lastname = null;
    #[Column(name:"email", type: Types::STRING, length: 255)]
    private $email;
    #[Column(name:"is_verified", type: Types::BOOLEAN, length: 255)]
    private $verified;
    #[Column(name:"is_active", type: Types::BOOLEAN, length: 255)]
    private $active;
    #[Column(name:"created_at", type: Types::BOOLEAN, length: 255)]
    protected $created;
    #[Column(name:"last_login",length: 255)]
    protected $lastLogin;
    #[Column(name:"updated_at",length: 255)]
    protected $updated;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=50)
     */
    private $phoneNumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     * @return User
     */
    public function setFirstname(?string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     * @return User
     */
    public function setLastname(?string $lastname): User
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * @param mixed $verified
     * @return User
     */
    public function setVerified($verified): User
    {
        $this->verified = $verified;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     * @return User
     */
    public function setActive($active): User
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     * @return User
     */
    public function setCreated($created): User
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin): User
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     * @return User
     */
    public function setUpdated($updated): User
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string|null $phoneNumber
     * @return User
     */
    public function setPhoneNumber(?string $phoneNumber): User
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'username'=> $this->username,
            'verified'=> $this->verified,
            'active'=> $this->active,
        );
    }

    #[PrePersist]
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }

    #[PreUpdate]
    public function onPreUpdate()
    {
        $this->updated = new \DateTime("now");
    }
}