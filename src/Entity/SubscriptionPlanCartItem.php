<?php

namespace App\Entity;

use App\Repository\SubscriptionPlanCartItemRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: SubscriptionPlanCartItemRepository::class)]
class SubscriptionPlanCartItem extends CartItem implements CartItemInterface
{
    #[ManyToOne(targetEntity: SubscriptionPlan::class)]
    #[JoinColumn(referencedColumnName: 'plan_id')]
    private CartInsertableInterface $referenceEntity;

    public function getType(): string
    {
        return 'plan';
    }

    public function getReferenceEntity(): CartInsertableInterface
    {
        return $this->referenceEntity;
    }

    public function setReferenceEntity(CartInsertableInterface $plan): void
    {
        $this->referenceEntity = $plan;
    }

    public function getItemName(): string
    {
        return $this->referenceEntity->getName();
    }

    public function getReferencedEntity(): CartInsertableInterface
    {
        // TODO: Implement getReferencedEntity() method.
    }

    public function setReferencedEntity(CartInsertableInterface $entity): CartItemInterface
    {
        // TODO: Implement setReferencedEntity() method.
    }

    public function toCartItem(): CartItem
    {
        // TODO: Implement toCartItem() method.
    }

    public function getQuantity(): int
    {
        // TODO: Implement getQuantity() method.
    }

    public function setQuantity(int $quantity): CartItemInterface
    {
        // TODO: Implement setQuantity() method.
    }
}
