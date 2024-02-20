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
        private readonly EntityManagerInterface $entityRepository,
        private readonly LoggerInterface $logger,
    ) {}

    public function create(string $entityType, int $id): CartItemInterface
    {
        try {
            $entity = $this->entityRepository->getRepository($entityType)
                ->find($id)
            ;
            assert($entity instanceof CartInsertableInterface);
            if (null === $entity) {
                throw new ItemNotFoundException();
            }

            return $entity->toCartItem();
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
