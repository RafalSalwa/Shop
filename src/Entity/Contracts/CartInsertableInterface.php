<?php

declare(strict_types=1);

namespace App\Entity\Contracts;

interface CartInsertableInterface
{
    public function getId(): int;

    public function getPrice(): int;

    public function getName(): string;
}
