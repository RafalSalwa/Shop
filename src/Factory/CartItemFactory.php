<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\CartInsertableInterface;
use App\Entity\CartItemInterface;
use App\Exception\ItemNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use function assert;

class CartItemFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {}

    public function create(string $entityType, int $id): CartItemInterface
    {
        try {
            $entity = $this->entityManager->getRepository($entityType)
                ->find($id)
            ;
            assert($entity instanceof CartInsertableInterface);

            return $entity->toCartItem();
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage(), $throwable->getTrace());
        }
    }
}
