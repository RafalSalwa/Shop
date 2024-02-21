<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Address::class);
    }

    /**
     * @param       mixed|null $address
     * @psalm-param \App\Repository\T|null $address
     */
    public function save(mixed $address): void
    {
        $this->getEntityManager()
            ->persist($address)
        ;
        $this->getEntityManager()
            ->flush()
        ;
    }
}
