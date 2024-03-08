<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Payment::class);
    }

    public function findById(int $id)
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

    public function save(Payment $payment): void
    {
        $this->getEntityManager()
            ->persist($payment)
        ;
        $this->getEntityManager()
            ->flush();
    }
}
