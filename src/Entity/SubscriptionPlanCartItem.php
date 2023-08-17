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
    private $destinationEntity;

    public function getType(): ?string
    {
        return 'plan';
    }

    public function getDestinationEntity()
    {
        return $this->destinationEntity;
    }

    public function setDestinationEntity(CartInsertableInterface $plan)
    {
        $this->destinationEntity = $plan;
    }
}
