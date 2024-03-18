<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SubscriptionPlanCartItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class SubscriptionPlanCartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SubscriptionPlanCartItem::class);
    }
}
