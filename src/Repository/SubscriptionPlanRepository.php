<?php

declare(strict_types=1);

namespace App\Repository;

use App\Config\Cache as ConfigCache;
use App\Entity\SubscriptionPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Cache;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

use function mb_strtolower;

final class SubscriptionPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SubscriptionPlan::class);
    }

    /** @throws NonUniqueResultException */
    public function findById(int $id): ?SubscriptionPlan
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /** @return array<SubscriptionPlan>|null */
    public function fetchAvailablePlans(): ?array
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.isVisible = true')
            ->orderBy('p.tier', 'ASC')
            ->setCacheMode(Cache::MODE_GET)
            ->setCacheable(true)
            ->getQuery();
        $query->enableResultCache(ConfigCache::DEFAULT_TTL, 'subscription_plans');

        return $query->getResult();
    }

    public function createFreemiumPlan(): SubscriptionPlan
    {
        return $this->getByName('freemium');
    }

    public function getByName(string $type): ?SubscriptionPlan
    {
        return $this->findOneBy(
            [
                'name' => mb_strtolower($type),
            ],
        );
    }
}
