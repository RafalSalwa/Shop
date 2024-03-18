<?php

declare(strict_types=1);

namespace App\Entity\Contracts;

interface StockManageableInterface extends CartInsertableInterface
{
    public function getId(): int;

    public function decreaseStock(int $quantity): void;

    public function increaseStock(int $quantity): void;

    public function changeStock(int $quantity): void;

    public function getUnitsInStock(): int;
}
