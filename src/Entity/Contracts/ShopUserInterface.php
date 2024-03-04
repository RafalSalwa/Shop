<?php

declare(strict_types=1);

namespace App\Entity\Contracts;

use App\Entity\Subscription;
use App\ValueObject\Token;
use Symfony\Component\Security\Core\User\UserInterface;

interface ShopUserInterface extends UserInterface
{
    public function getId(): ?int;

    public function getEmail(): string;

    public function getSubscription(): Subscription;

    public function getToken(): Token;

    public function getRefreshToken(): Token;

    public function setToken(Token $token): void;

    public function setRefreshToken(Token $refreshToken): void;

    public function setAuthCode(string $code): void;
}
