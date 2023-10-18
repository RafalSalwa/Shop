<?php

declare(strict_types=1);

namespace App\Entity;

interface CartInsertableInterface
{
    public function getId(): int;

    public function toCartItem(): CartItemInterface;

    public function getName(): string;
}
