<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Address::class);
    }

    public function save(Address $address): void
    {
        $this->getEntityManager()->persist($address);
        $this->getEntityManager()->flush();
    }

    /**
     * Checks for default(isDefault=true) address, if not, takes latest address, otherwise return null.
     */
    public function getDefaultForUser(int $userId): ?Address
    {
        $defaultAddress = $this
            ->createQueryBuilder('a')
            ->where('a.userId = :id')
            ->andWhere('a.isDefault = true')
            ->setParameter('id', $userId)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $defaultAddress) {
            $defaultAddress = $this
                ->createQueryBuilder('a')
                ->where('a.userId = :id')
                ->setParameter('id', $userId)
                ->orderBy('a.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }

        return $defaultAddress;
    }

    public function setDefaultAddress(int|string $addressId, int $userId): void
    {
        $qb = $this->createQueryBuilder('a');
        $qb->update()
            ->set('a.isDefault', $qb->expr()->literal(false))
            ->where('a.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()->execute();

        $this
            ->createQueryBuilder('a')
            ->update($this->getEntityName(), 'a')
            ->set('a.isDefault', $qb->expr()->literal(true))
            ->where('a.userId = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('a.id = :addressId')
            ->setParameter('addressId', $addressId)
            ->getQuery()
            ->execute()
        ;
    }
}
