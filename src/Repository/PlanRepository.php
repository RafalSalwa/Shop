<?php

namespace App\Repository;

use App\Entity\Plan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class PlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plan::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findById(int $id)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->andWhere()
            ->orWhere()
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
