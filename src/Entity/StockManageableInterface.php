<?php

namespace App\Entity;

interface StockManageableInterface
{
    public function decreaseStock(StockManageableInterface $product, int $quantity): self;

    public function increaseStock(StockManageableInterface $product, int $quantity): self;

    public function changeStock(StockManageableInterface $product, int $quantity): self;
}
