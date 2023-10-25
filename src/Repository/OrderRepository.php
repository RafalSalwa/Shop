<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function save(Order $order)
    {
        $this->getEntityManager()
            ->persist($order);
        $this->getEntityManager()
            ->flush();
    }

    public function fetchOrderDetails(int $id): ?Order
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('o', 'i', 'p', 'a')
            ->leftJoin('o.items', 'i')
            ->leftJoin('o.payments', 'p')
            ->leftJoin('o.address', 'a')
            ->where('o.id = :id')
            ->orderBy('o.createdAt', 'DESC')
            ->setParameter('id', $id);

        return $qb->getQuery()
            ->getOneOrNullResult();
    }

    public function fetchOrders(User $user, int $page)
    {
        $qb = $this->createQueryBuilder('o')
            ->addSelect('o', 'i', 'p', 'a')
            ->leftJoin('o.items', 'i')
            ->leftJoin('o.payments', 'p')
            ->leftJoin('o.address', 'a')
            ->where('o.user = :user')
            ->orderBy('o.status', 'DESC')
            ->addOrderBy('o.createdAt', 'DESC')
            ->setParameter('user', $user);

        return (new Paginator($qb))->paginate($page);
    }
}
