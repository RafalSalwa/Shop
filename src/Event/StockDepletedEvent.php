<?php

namespace App\Event;

use App\Entity\StockManageableInterface;
use Symfony\Contracts\EventDispatcher\Event;

class StockDepletedEvent extends Event
{
    private array $arrProd = [];

    public function __construct(StockManageableInterface $product)
    {
        $prod = $product->getReferenceEntity();
        $this->arrProd = [
            "id" => $prod->getId(),
            "name" => $prod->getName(),
            "category" => $prod->getCategory()->getName()
        ];
    }

    public function getEventData(): array
    {
        return $this->arrProd;
    }
}
