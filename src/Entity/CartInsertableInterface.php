<?php

declare(strict_types=1);

namespace App\Entity;

interface CartInsertableInterface
{
    public function getId(): int;

    public function toCartItem(): CartItemInterface;

    public function getUnitPrice(bool $userFriendly = false): int|float;

    public function getName(): string;
}
