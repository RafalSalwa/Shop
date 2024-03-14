<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Subscription::class);
    }

    public function remove(Subscription $subscription): void
    {
        $this->getEntityManager()
            ->remove($subscription)
        ;
        $this->getEntityManager()
            ->flush()
        ;
    }

    public function findForUser(int|string $userId): ?Subscription
    {
        return $this->findOneBy(
            [
                'userId' => $userId,
                'isActive' => true,
            ],
        );
    }

    public function assignSubscription(int $getUserId, SubscriptionPlan $plan): void
    {
        $subscription = $this->createSubscription($plan);
        $subscription->setUserId($getUserId);
        $this->save($subscription);
    }

    public function createSubscription(SubscriptionPlan $subscriptionPlan): Subscription
    {
        $subscription = new Subscription();
        $subscription->setPlan($subscriptionPlan);

        return $subscription;
    }

    public function save(Subscription $subscription): void
    {
        $this->getEntityManager()
            ->persist($subscription)
        ;
        $this->getEntityManager()
            ->flush()
        ;
    }

    public function clearSubscriptionsForUserId(int $userId): void
    {
        $qb = $this->createQueryBuilder('s');
        $qb->update()
            ->set('s.isActive', $qb->expr()->literal(false))
            ->where('s.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->execute();
    }
}
