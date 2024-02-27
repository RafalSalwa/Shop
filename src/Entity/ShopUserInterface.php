<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

interface ShopUserInterface extends UserInterface
{
    public function getId(): ?int;

    public function getEmail(): string;

    public function getSubscription(): ?Subscription;
}
