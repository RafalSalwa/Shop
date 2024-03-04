<?php

declare(strict_types=1);

namespace App\Entity\Contracts;

interface CartInsertableInterface
{
    public function getId(): int;

    public function toCartItem(): CartItemInterface;

    public function getPrice(): float|int;

    public function getName(): string;
}
