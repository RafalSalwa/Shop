<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\Subscription;
use App\ValueObject\EmailAddress;
use App\ValueObject\Token;
use JsonSerializable;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function array_key_exists;
use function array_unique;

final class User implements JsonSerializable, UserInterface, ShopUserInterface, EquatableInterface
{

    private EmailAddress $email;

    private ?Token $token = null;

    private ?Token $refreshToken = null;

    private string $authCode;

    private Subscription $subscription;

    /**
     * Roles property to meet UserInterface requirements.
     *
     * @var array<int,string>
     */
    private array $roles;

    public function __construct(
        private readonly int $id,
        string $email,
        ?string $authCode = null,
        ?string $token = null,
        ?string $refreshToken = null,
    ) {
        $this->email = new EmailAddress($email);

        if (null !== $token) {
            $this->setToken(new Token($token));
        }
        if (null !== $refreshToken) {
            $this->setRefreshToken(new Token($refreshToken));
        }
        if (null !== $authCode) {
            $this->setAuthCode($authCode);
        }
        $this->setRoles(['ROLE_USER']);
    }

    public function setAuthCode(string $code): void
    {
        $this->authCode = $code;
    }

    public function getUserIdentifier(): string
    {
        return $this->getToken()->value();
    }

    public function getToken(): Token
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
        $roles = $this->roles;
        if (null !== $roles) {
            $roles = array_key_exists('roles', $this->roles)
                ? $this->roles['roles']
                : $this->roles;

            return array_unique($roles);
        }

        return array_unique($this->roles);
    }

    public function setRoles(?array $roles): void
    {
        $this->roles = $roles;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
        ];
    }

    public function eraseCredentials(): void
    {}

    public function getRefreshToken(): Token
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(Token $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    public function getSubscription(): Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(Subscription $subscription): void
    {
        $this->subscription = $subscription;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        if ($this->getId() !== $user->getId()) {
            return false;
        }

        return $this->getEmail() === $user->getEmail();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email->toString();
    }

    public function setEmail(EmailAddress $email): void
    {
        $this->email = $email;
    }

}
