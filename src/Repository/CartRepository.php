<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null   find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<Cart> findAll()
 * @method array<Cart> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Cart::class);
    }

    public function save(Cart $cart): void
    {
        $this->getEntityManager()->persist($cart);
        $this->getEntityManager()->flush();
    }
}
