<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderItem>
 *
 * @method OrderItem|null   find($id, $lockMode = null, $lockVersion = null)
 * @method OrderItem|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<OrderItem> findAll()
 * @method array<OrderItem> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, OrderItem::class);
    }
}
