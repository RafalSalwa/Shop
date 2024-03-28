<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CartItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartItem>
 *
 * @method CartItem|null   find($id, $lockMode = null, $lockVersion = null)
 * @method CartItem|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<CartItem> findAll()
 * @method array<CartItem> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, CartItem::class);
    }
}
