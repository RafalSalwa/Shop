<?php

namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class StockDepletedEvent extends Event
{
    private array $arrProd = [];

    public function __construct(Product $product)
    {
        $this->arrProd = [
            "id" => $product->getId(),
            "name" => $product->getName(),
            "category" => $product->getCategory()->getName()
        ];

    }

    public function getEventData(): array
    {
        return $this->arrProd;
    }

}