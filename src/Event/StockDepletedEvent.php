<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Contracts\StockManageableInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class StockDepletedEvent extends Event
{
    public function __construct(private readonly StockManageableInterface $item)
    {}

    public function getItem(): StockManageableInterface
    {
        return $this->item;
    }
}
