<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SubscriptionPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Cache;
use Doctrine\Persistence\ManagerRegistry;
use function mb_strtolower;

class SubscriptionPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SubscriptionPlan::class);
    }

    public function findById(int $id)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
        ;

        return $queryBuilder->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function fetchAvailablePlans()
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.isVisible = true')
            ->orderBy('p.createdAt', 'DESC')
            ->setCacheMode(Cache::MODE_GET)
            ->setCacheable(true)
            ->getQuery()
        ;
        $query->enableResultCache(86400, 'subscription_plans');

        return $query->getResult();
    }

    public function createSubscription(string $string): void
    {}

    public function getByName(string $type)
    {
        return $this->findOneBy(
            [
                'planName' => mb_strtolower($type),
            ],
        );
    }
}
