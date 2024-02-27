<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

final class OrderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, OrderItem::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findById(int $id): mixed
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('id', $id)
        ;

        return $queryBuilder->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
