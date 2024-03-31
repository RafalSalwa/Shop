<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AbstractCartItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AbstractCartItem>
 *
 * @method AbstractCartItem|null   find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractCartItem|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<AbstractCartItem> findAll()
 * @method array<AbstractCartItem> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, AbstractCartItem::class);
    }
}
