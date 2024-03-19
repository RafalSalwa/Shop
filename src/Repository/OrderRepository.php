<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

final class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Order::class);
    }

    public function save(Order $order): void
    {
        $this->getEntityManager()
            ->persist($order)
        ;
        $this->getEntityManager()
            ->flush()
        ;
    }

    /** @throws NonUniqueResultException */
    public function fetchOrderDetails(int $id): ?Order
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('o', 'i', 'p', 'da', 'ba')
            ->leftJoin('o.items', 'i')
            ->leftJoin('o.payments', 'p')
            ->leftJoin('o.deliveryAddress', 'da')
            ->leftJoin('o.bilingAddress', 'ba')
            ->where('o.id = :id')
            ->orderBy('o.createdAt', 'DESC')
            ->setParameter('id', $id)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult()
        ;
    }

    /** @param array<string> $status */
    public function fetchOrders(int $userId, int $page, array $status): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('o', 'i', 'p', 'da', 'ba')
            ->leftJoin('o.items', 'i')
            ->leftJoin('o.payments', 'p')
            ->leftJoin('o.deliveryAddress', 'da')
            ->leftJoin('o.bilingAddress', 'ba')
            ->where('o.userId = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('o.status IN (:status)')
            ->setParameter('status', $status)
            ->orderBy('o.status', 'DESC')
            ->addOrderBy('o.createdAt', 'DESC')
        ;

        return (new Paginator($queryBuilder))->paginate($page);
    }
}
