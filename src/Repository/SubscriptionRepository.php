<?php

namespace App\Repository;

use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function save(Subscription $subscription): void
    {
        $this->getEntityManager()->persist($subscription);
        $this->getEntityManager()->flush();
    }

    public function remove(Subscription $subscription): void
    {
        $this->getEntityManager()->remove($subscription);
        $this->getEntityManager()->flush();
    }

    public function createSubscription(SubscriptionPlan $plan)
    {
        $subscription = new Subscription();
        $subscription->setSubscriptionPlan($plan);
        return $subscription;
    }
}
