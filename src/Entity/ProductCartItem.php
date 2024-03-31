<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\CartInsertableInterface;
use App\Entity\Contracts\CartItemInterface;
use App\Repository\ProductCartItemRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: ProductCartItemRepository::class)]
class ProductCartItem extends AbstractCartItem implements CartItemInterface
{
    #[ManyToOne(targetEntity: Product::class)]
    #[JoinColumn(referencedColumnName: 'product_id', nullable: false)]
    protected Product $referencedEntity;

    public function __construct(Cart $cart, Product $referencedEntity, int $quantity)
    {
        parent::__construct($cart);

        $this->referencedEntity = $referencedEntity;
        $this->quantity = $quantity;
    }

    final public function getReferencedEntity(): CartInsertableInterface
    {
        return $this->referencedEntity;
    }

    final public function getName(): string
    {
        return $this->referencedEntity->getName();
    }
}
