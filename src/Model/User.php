<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\OAuth2UserConsent;
use App\Entity\Subscription;
use App\Exception\AuthException;
use App\ValueObject\EmailAddress;
use App\ValueObject\Token;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use function assert;

final class User implements UserInterface, ShopUserInterface, EquatableInterface
{
    private readonly EmailAddress $email;

    private ?Token $token = null;

    private ?Token $refreshToken = null;

    private ?Subscription $subscription = null;

    /**
     * Roles property to meet UserInterface requirements.
     *
     * @var array<int,string>
     */
    private array $roles;

    /** @var Collection<int, OAuth2UserConsent> */
    private readonly Collection $consents;

    public function __construct(string $email)
    {
        $this->email = new EmailAddress($email);

        $this->consents = new ArrayCollection();
        $this->setRoles(['ROLE_USER']);
    }

    /** @throws AuthException */
    public function getUserIdentifier(): string
    {
        if (null === $this->token) {
            throw new AuthException('Token not provided.');
        }

        return $this->token->value();
    }

    public function getToken(): ?Token
    {
        return $this->token;
    }

    public function setToken(Token $token): void
    {
        $this->token = $token;
    }

    /** @return array<int,string> */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /** @param array<string> $roles */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function eraseCredentials(): void
    {
        // We are not storing any sensitive data so there no need to erase anything
    }

    public function getRefreshToken(): ?Token
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(Token $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(Subscription $subscription): void
    {
        $this->subscription = $subscription;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        assert($user instanceof ShopUserInterface);
        if ($this->getId() !== $user->getId()) {
            return false;
        }

        return $this->getEmail() === $user->getEmail();
    }

    public function getId(): int
    {
        return (int)$this->token?->getSub();
    }

    public function getEmail(): string
    {
        return $this->email->toString();
    }

    /** @return Collection<int, OAuth2UserConsent> */
    public function getConsents(): Collection
    {
        return $this->consents;
    }

    public function addConsent(OAuth2UserConsent $consent): void
    {
        $this->consents->add($consent);
    }
}
