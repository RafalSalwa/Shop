<?php

declare(strict_types=1);

namespace App\Entity;

interface StockManageableInterface
{
    public function decreaseStock(self $product, int $quantity): self;

    public function increaseStock(self $product, int $quantity): self;

    public function changeStock(self $product, int $quantity): self;

    public function getUnitsInStock(): int;
}
