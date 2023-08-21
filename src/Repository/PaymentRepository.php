<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findById(int $id)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function save(Payment $payment)
    {
        $this->getEntityManager()->persist($payment);
        $this->getEntityManager()->flush();
    }
}
