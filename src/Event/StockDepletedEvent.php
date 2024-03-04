<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Contracts\StockManageableInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class StockDepletedEvent extends Event
{
    private array $arrProd = [];

    public function __construct(StockManageableInterface $stockManageable)
    {
        $prod = $stockManageable->getReferenceEntity();
        $this->arrProd = [
            'id' => $prod->getId(),
            'name' => $prod->getName(),
            'category' => $prod->getCategory()
                ->getName(),
        ];
    }

    public function getEventData(): array
    {
        return $this->arrProd;
    }
}
