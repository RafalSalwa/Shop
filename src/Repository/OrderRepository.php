<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function fetchOrderDetails(int $id): ?Order
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('o', 'i', 'p', 'a')
            ->leftJoin('o.items', 'i')
            ->leftJoin('o.payments', 'p')
            ->leftJoin('o.address', 'a')
            ->where('o.id = :id')
            ->orderBy('o.createdAt', 'DESC')
            ->setParameter('id', $id)
        ;

        return $queryBuilder->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function fetchOrders(User $user, int $page): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('o', 'i', 'p', 'a')
            ->leftJoin('o.items', 'i')
            ->leftJoin('o.payments', 'p')
            ->leftJoin('o.address', 'a')
            ->where('o.user = :user')
            ->orderBy('o.status', 'DESC')
            ->addOrderBy('o.createdAt', 'DESC')
            ->setParameter('user', $user)
        ;

        return (new Paginator($queryBuilder))->paginate($page);
    }
}
