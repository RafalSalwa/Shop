<?php

namespace App\Repository;

use App\Entity\SubscriptionPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Cache;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class PlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubscriptionPlan::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findById(int $id)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function fetchAvailablePlans()
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.isVisible = true')
            ->orderBy('p.createdAt', 'DESC')
            ->setCacheMode(Cache::MODE_GET)
            ->setCacheable(true)
            ->getQuery();
        $query->enableResultCache(86400, 'subscription_plans');
        return $query->getResult();
    }
}
