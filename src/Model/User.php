<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\OAuth2UserConsent;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Subscription;
use App\Model\ApiTokenPair;
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
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function array_key_exists;
use function array_unique;

class User implements JsonSerializable, UserInterface
{
    private ?int $id = null;

    private string $username;

    private string $password = '';

    private ?string $firstname = null;

    private ?string $lastname = null;

    private string $email;

    private ?string $phoneNo = null;

    private ?array $roles = null;

    private DateTimeImmutable $createdAt;

    private ?DateTime $updatedAt = null;

    private ?DateTimeImmutable $deletedAt = null;

    private ?DateTime $lastLogin = null;

    private ?Collection $oAuth2UserConsents = null;

    private ?Collection $carts = null;

    private ?Collection $deliveryAddresses;

    private ?Collection $payments = null;

    private ?Collection $orders = null;

    private ?Subscription $subscription = null;

    protected ?string $token = null;
    protected ?string $refreshToken = null;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->deliveryAddresses = new ArrayCollection();
        $this->payments = new ArrayCollection();
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

    public function setPayments(?Collection $payments): self
    {
        $this->payments = $payments;

        return $this;
    }

    public function addPayment(Payment $payment): self
    {
        $this->payments->add($payment);

        return $this;
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

    public function getId(): ?int
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

    public function setUsername(string $username): User
    {
        $this->username = $username;

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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        if (null !== $this->roles){
        $roles = array_key_exists('roles', $this->roles) ? $this->roles['roles'] : $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);}
        return [];
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;

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

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    /**
 * @return User 
*/
    public function setLastLogin(DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
 * @return User 
*/
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

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->id;
    }

    public function getOAuth2UserConsents(): ?Collection
    {
        return $this->oAuth2UserConsents;
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


    public function setOAuth2UserConsents(?Collection $oAuth2UserConsents): User
    {
        $this->oAuth2UserConsents = $oAuth2UserConsents;

        return $this;
    }

    public function setCarts(?Collection $carts): User
    {
        $this->carts = $carts;

        return $this;
    }

    public function setOrders(?Collection $orders): User
    {
        $this->orders = $orders;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): User
    {
        $this->token = $token;
        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): User
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function setFromAuthApi(ResponseInterface $response): void
    {
        $arrResponse = json_decode($response->getContent(), true);
        $this->setId($arrResponse['user']['id']);
        $this->setEmail($arrResponse['user']['email']);

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
}
