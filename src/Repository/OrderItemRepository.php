<?php

namespace App\Repository;

use App\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findById(int $id)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
