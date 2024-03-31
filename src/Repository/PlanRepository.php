<?php

declare(strict_types=1);

namespace App\Repository;

use App\Config\Cache as ConfigCache;
use App\Entity\SubscriptionPlan;
use App\Exception\ItemNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Cache;
use Doctrine\Persistence\ManagerRegistry;

use function mb_strtolower;

/**
 * @extends ServiceEntityRepository<SubscriptionPlan>
 *
 * @method SubscriptionPlan|null   find($id, $lockMode = null, $lockVersion = null)
 * @method SubscriptionPlan|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<SubscriptionPlan> findAll()
 * @method array<SubscriptionPlan> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, SubscriptionPlan::class);
    }

    /**
     * @throws ItemNotFoundException
     * @return array<SubscriptionPlan>
     */
    public function fetchAvailablePlans(): array
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.isVisible = true')
            ->orderBy('p.tier', 'ASC')
            ->setCacheMode(Cache::MODE_GET)
            ->setCacheable(true)
            ->getQuery()
        ;
        $query->enableResultCache(ConfigCache::DEFAULT_TTL, 'subscription_plans');

        $plans = $query->getResult();
        if ([] === $plans) {
            throw new ItemNotFoundException(' There is no active subscription plan.');
        }

        return $plans;
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
