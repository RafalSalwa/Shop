<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\CartInsertableInterface;
use App\Repository\SubscriptionPlanCartItemRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: SubscriptionPlanCartItemRepository::class)]
class SubscriptionPlanCartItem extends AbstractCartItem
{
    #[ManyToOne(targetEntity: SubscriptionPlan::class)]
    #[JoinColumn(referencedColumnName: 'plan_id')]
    protected CartInsertableInterface $referencedEntity;
}
