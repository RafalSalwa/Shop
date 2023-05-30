<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

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
    #[Column(name:"first_name", type: Types::STRING, length: 255, nullable:true)]
    private ?string $firstname = null;
    #[Column(name:"last_name", type: Types::STRING, length: 255, nullable:true)]
    private ?string $lastname = null;
    #[Column(name:"email", type: Types::STRING, length: 255)]
    private $email;
    #[Column(name:"phone_no", type: Types::STRING, length: 11, nullable:true)]
    private $phoneNo;
    #[Column(name:"roles", type: Types::JSON, length: 255, nullable:true)]
    private $roles;
    #[Column(name:"verification_code", type: Types::STRING, length: 6)]
    private $verificationCode;
    #[Column(name:"is_verified", type: Types::BOOLEAN, length: 255, options: ["default" =>false])]
    private $verified;
    #[Column(name:"is_active", type: Types::BOOLEAN, length: 255, options: ["default" =>false])]
    private $active;
    #[Column(name:"created_at", type: Types::DATETIME_MUTABLE, options: ["default" =>"CURRENT_TIMESTAMP"])]
    protected $createdAt;
    #[Column(name:"updated_at",type: Types::DATETIME_MUTABLE, nullable: true)]
    protected $updatedAt;
    #[Column(name:"deleted_at", type: Types::DATETIME_MUTABLE, nullable: true)]
    protected $deletedAt;
    #[Column(name:"last_login",type: Types::DATETIME_MUTABLE, nullable: true)]
    protected $lastLogin;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

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
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneNo()
    {
        return $this->phoneNo;
    }

    /**
     * @param mixed $phoneNo
     * @return User
     */
    public function setPhoneNo($phoneNo)
    {
        $this->phoneNo = $phoneNo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVerificationCode()
    {
        return $this->verificationCode;
    }

    /**
     * @param mixed $verificationCode
     * @return User
     */
    public function setVerificationCode($verificationCode)
    {
        $this->verificationCode = $verificationCode;
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
    public function setVerified($verified)
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
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param mixed $deletedAt
     * @return User
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
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
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }


    public function jsonSerialize() : mixed
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
        $this->createdAt = new \DateTime("now");
    }

    #[PreUpdate]
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime("now");
    }
}