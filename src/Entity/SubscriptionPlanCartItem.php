<?php

namespace App\Entity;

use App\Repository\SubscriptionPlanCartItemRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: SubscriptionPlanCartItemRepository::class)]
class SubscriptionPlanCartItem extends CartItem
{
    #[ManyToOne(targetEntity: SubscriptionPlan::class)]
    #[JoinColumn(referencedColumnName: 'plan_id')]
    private CartInsertableInterface $destinationEntity;

    public function getType(): ?string
    {
        return 'plan';
    }

    public function getDestinationEntity(): CartInsertableInterface
    {
        return $this->destinationEntity;
    }

    public function setDestinationEntity(CartInsertableInterface $plan): void
    {
        $this->destinationEntity = $plan;
    }

    public function getItemName(): string
    {
        return $this->destinationEntity->getName();
    }
}
