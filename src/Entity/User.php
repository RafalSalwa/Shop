<?php

declare(strict_types=1);

namespace App\Entity;

use App\Model\TokenPair;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
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
use Doctrine\ORM\Mapping\SequenceGenerator;
use JsonSerializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use function array_key_exists;
use function array_unique;
use function mb_substr;
use function str_repeat;
use function str_shuffle;

#[Entity(repositoryClass: UserRepository::class)]
#[HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'address is already in use')]
final class User implements JsonSerializable, UserInterface, PasswordAuthenticatedUserInterface
{

    public $isVerified;

    protected TokenPair $tokenPair;

    protected string $refreshToken;

    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'user_id', type: Types::INTEGER, unique: true)]
    #[SequenceGenerator(sequenceName: 'user_userID_seq', allocationSize: 1, initialValue: 1)]
    private int|null $id = null;

    #[Column(name: 'username', type: Types::STRING, length: 180, nullable: true)]
    private string $username;

    #[Column(name: 'pass', type: Types::STRING, length: 100)]
    private string $password;

    #[Column(name: 'first_name', type: Types::STRING, length: 255, nullable: true)]
    private string|null $firstname = null;

    #[Column(name: 'last_name', type: Types::STRING, length: 255, nullable: true)]
    private string|null $lastname = null;

    #[Column(name: 'email', type: Types::STRING, length: 255)]
    private string $email;

    #[Column(name: 'phone_no', type: Types::STRING, length: 11, nullable: true)]
    private string|null $phoneNo = null;

    #[Column(name: 'roles', type: Types::JSON, length: 255, nullable: true)]
    private array|null $roles = null;

    #[Column(name: 'verification_code', type: Types::STRING, length: 12)]
    private string $verificationCode;

    #[Column(
        name: 'is_verified',
        type: Types::BOOLEAN,
        options: ['default' => false],
    )]
    private bool $verified = false;

    #[Column(
        name: 'is_active',
        type: Types::BOOLEAN,
        options: ['default' => false],
    )]
    private bool $active = false;

    #[Column(
        name: 'created_at',
        type: Types::DATETIME_IMMUTABLE,
        options: ['default' => 'CURRENT_TIMESTAMP'],
    )]
    private DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime|null $updatedAt = null;

    #[Column(name: 'deleted_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private DateTimeImmutable|null $deletedAt = null;

    #[Column(name: 'last_login', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime|null $lastLogin = null;

    /** @var Collection<int, OAuth2UserConsent>|null */
    #[OneToOne(targetEntity: OAuth2UserConsent::class)]
    #[JoinColumn(name: 'consent_id', referencedColumnName: 'id')]
    private Collection|null $oAuth2UserConsents = null;

    /** @var Collection<int, Cart> */
    #[OneToMany(mappedBy: 'user', targetEntity: Cart::class)]
    #[Groups('user_carts')]
    private Collection|null $carts = null;

    /** @var Collection<int, Address> */
    #[OneToMany(mappedBy: 'user', targetEntity: Address::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection|null $deliveryAddresses;

    /** @var Collection<int, Payment> */
    #[OneToMany(mappedBy: 'user', targetEntity: Payment::class)]
    private Collection|null $payments = null;

    /** @var \Doctrine\Common\Collections\Collection<int, Order> */
    #[OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private Collection|null $orders = null;

    #[OneToOne(targetEntity: Subscription::class, fetch: 'EAGER')]
    #[JoinColumn(name: 'subscription_id', referencedColumnName: 'subscription_id', nullable: true)]
    private Subscription|null $subscription = null;

    public function __construct()
    {
        $this->oAuth2UserConsents = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->carts             = new ArrayCollection();
        $this->deliveryAddresses = new ArrayCollection();
        $this->payments          = new ArrayCollection();
        $this->verificationCode  = mb_substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz', 5)), 0, 5);
    }

    public function getSubscription(): Subscription|null
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

    public function getCarts(): Collection|null
    {
        return $this->carts;
    }

    public function setCarts(Collection|null $carts): self
    {
        $this->carts = $carts;

        return $this;
    }

    public function getPayments(): Collection|null
    {
        return $this->payments;
    }

    public function setPayments(Collection|null $payments): self
    {
        $this->payments = $payments;

        return $this;
    }

    public function addPayment(Payment $payment): self
    {
        $this->payments->add($payment);

        return $this;
    }

    public function getOrders(): Collection|null
    {
        return $this->orders;
    }

    public function setOrders(Collection|null $orders): self
    {
        $this->orders = $orders;

        return $this;
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

    public function getId(): int|null
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

    public function getFirstname(): string|null
    {
        return $this->firstname;
    }

    public function setFirstname(string|null $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): string|null
    {
        return $this->lastname;
    }

    public function setLastname(string|null $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNo(): string|null
    {
        return $this->phoneNo;
    }

    public function setPhoneNo(string|null $phoneNo): self
    {
        $this->phoneNo = $phoneNo;

        return $this;
    }

    public function getRoles(): array
    {
        if (null !== $this->roles) {
            $roles = array_key_exists('roles', $this->roles) ? $this->roles['roles'] : $this->roles;

            $roles[] = 'ROLE_USER';

            return array_unique($roles);
        }

        return [];
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }

    public function setVerificationCode(string $verificationCode): self
    {
        $this->verificationCode = $verificationCode;

        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(mixed $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeletedAt(): DateTimeImmutable|null
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getLastLogin(): DateTime|null
    {
        return $this->lastLogin;
    }

    public function setLastLogin(DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getUpdatedAt(): DateTime|null
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
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
        $this->createdAt = new DateTimeImmutable('now');
    }

    #[PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTime('now');
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->id;
    }

    public function getOAuth2UserConsents(): Collection|null
    {
        return $this->oAuth2UserConsents;
    }

    public function setOAuth2UserConsents(Collection|null $oAuth2UserConsents): self
    {
        $this->oAuth2UserConsents = $oAuth2UserConsents;

        return $this;
    }

    public function addOAuth2UserConsent(OAuth2UserConsent $oAuth2UserConsent): self
    {
        if (! $this->oAuth2UserConsents->contains($oAuth2UserConsent)) {
            $this->oAuth2UserConsents->add($oAuth2UserConsent);
            $oAuth2UserConsent->setUser($this);
        }

        return $this;
    }

    public function removeOAuth2UserConsent(OAuth2UserConsent $oAuth2UserConsent): self
    {
        if ($this->oAuth2UserConsents->removeElement($oAuth2UserConsent) && $oAuth2UserConsent->getUser() === $this) {
            $oAuth2UserConsent->setUser(null);
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
