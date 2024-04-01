<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function assert;

/**
 * @extends ServiceEntityRepository<Subscription>
 *
 * @method Subscription|null   find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<Subscription> findAll()
 * @method array<Subscription> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Subscription::class);
    }

    public function remove(Subscription $subscription): void
    {
        $this->getEntityManager()->remove($subscription);
        $this->getEntityManager()->flush();
    }

    public function findForUser(int $userId): Subscription
    {
        $subscription = $this->findOneBy(
            [
                'userId' => $userId,
                'isActive' => true,
            ],
        );
        assert($subscription instanceof Subscription);

        return $subscription;
    }

    public function assignSubscription(int $userId, SubscriptionPlan $plan): void
    {
        $subscription = $this->createSubscription($userId, $plan);
        $this->save($subscription);
    }

    public function createSubscription(int $userId, SubscriptionPlan $subscriptionPlan): Subscription
    {
        return new Subscription(userId: $userId, plan: $subscriptionPlan);
    }

    public function save(Subscription $subscription): void
    {
        $this->getEntityManager()->persist($subscription);
        $this->getEntityManager()->flush();
    }

    public function clearSubscriptions(int $userId): void
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->update()
            ->set('s.isActive', $queryBuilder->expr()->literal(false))
            ->where('s.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();
    }
}
