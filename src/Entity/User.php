<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

use function array_key_exists;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: 'intrv_user', schema: "interview")]
#[HasLifecycleCallbacks]
class User implements JsonSerializable, UserInterface, PasswordAuthenticatedUserInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'user_id', type: Types::INTEGER, unique: true)]
    private int $id;
    #[Column(name: 'username', type: Types::STRING, length: 180)]
    private string $username;
    #[Column(name: 'pass', type: Types::STRING, length: 100)]
    private string $password;
    #[Column(name: 'first_name', type: Types::STRING, length: 255, nullable: true)]
    private ?string $firstname = null;
    #[Column(name: 'last_name', type: Types::STRING, length: 255, nullable: true)]
    private ?string $lastname = null;
    #[Column(name: 'email', type: Types::STRING, length: 255)]
    private string $email;
    #[Column(name: 'phone_no', type: Types::STRING, length: 11, nullable: true)]
    private ?string $phoneNo = null;
    #[Column(name: 'roles', type: Types::JSON, length: 255, nullable: true)]
    private ?array $roles = null;
    #[Column(name: 'verification_code', type: Types::STRING, length: 12)]
    private string $verificationCode;
    #[Column(name: 'is_verified', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $verified;
    #[Column(name: 'is_active', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $active;
    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;
    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $updatedAt = null;
    #[Column(name: 'deleted_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $deletedAt = null;
    #[Column(name: 'last_login', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $lastLogin = null;
    #[OneToMany(mappedBy: 'user', targetEntity: OAuth2UserConsent::class, orphanRemoval: true)]
    private ?Collection $oAuth2UserConsents = null;
    #[OneToMany(mappedBy: 'user', targetEntity: Cart::class)]
    #[Groups('user_carts')]
    private ?Collection $carts = null;

    #[OneToMany(mappedBy: 'user', targetEntity: Address::class, cascade: ['persist'], orphanRemoval: true)]
    private ?Collection $deliveryAddresses;

    #[OneToMany(mappedBy: 'user', targetEntity: Payment::class)]
    private ?Collection $payments = null;
    #[OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private ?Collection $orders = null;

    #[OneToOne(targetEntity: Subscription::class, fetch: 'EAGER')]
    #[JoinColumn(name: 'subscription_id', referencedColumnName: 'subscription_id', nullable: true)]
    private ?Subscription $subscription = null;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->deliveryAddresses = new ArrayCollection();
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getDeliveryAddresses(): Collection|null
    {
        return $this->deliveryAddresses;
    }

    public function setDeliveryAddresses(ArrayCollection $deliveryAddresses): self
    {
        $this->deliveryAddresses = $deliveryAddresses;

        return $this;
    }

    public function getCarts(): ?Collection
    {
        return $this->carts;
    }

    public function getPayments(): ?Collection
    {
        return $this->payments;
    }

    public function getOrders(): ?Collection
    {
        return $this->orders;
    }

    public function addDeliveryAddress(Address $address): void
    {
        $address->setUser($this);
        $this->deliveryAddresses[] = $address;
    }

    public function removeDeliveryAddress(Address $address): void
    {
        $this->deliveryAddresses->removeElement($address);
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

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNo(): string|null
    {
        return $this->phoneNo;
    }

    /**
     * @return User
     */
    public function setPhoneNo($phoneNo)
    {
        $this->phoneNo = $phoneNo;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = array_key_exists('roles', $this->roles) ? $this->roles['roles'] : $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }

    /**
     * @return User
     */
    public function setVerificationCode($verificationCode)
    {
        $this->verificationCode = $verificationCode;

        return $this;
    }

    public function getVerified(): bool
    {
        return $this->verified;
    }

    /**
     * @return User
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;

        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeletedAt(): DateTime|null
    {
        return $this->deletedAt;
    }

    /**
     * @return User
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getLastLogin(): DateTime|null
    {
        return $this->lastLogin;
    }

    /**
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getUpdatedAt(): DateTime|null
    {
        return $this->updatedAt;
    }

    /**
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'verified' => $this->verified,
            'active' => $this->active,
        ];
    }

    #[PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime('now');
    }

    #[PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime('now');
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getOAuth2UserConsents(): Collection|null
    {
        return $this->oAuth2UserConsents;
    }

    public function addOAuth2UserConsent(OAuth2UserConsent $oAuth2UserConsent): self
    {
        if (!$this->oAuth2UserConsents->contains($oAuth2UserConsent)) {
            $this->oAuth2UserConsents->add($oAuth2UserConsent);
            $oAuth2UserConsent->setUser($this);
        }

        return $this;
    }

    public function removeOAuth2UserConsent(OAuth2UserConsent $oAuth2UserConsent): self
    {
        // set the owning side to null (unless already changed)
        if ($this->oAuth2UserConsents->removeElement($oAuth2UserConsent) && $oAuth2UserConsent->getUser() === $this) {
            $oAuth2UserConsent->setUser(null);
        }

        return $this;
    }
}
