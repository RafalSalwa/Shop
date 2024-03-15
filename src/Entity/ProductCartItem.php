<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\CartInsertableInterface;
use App\Repository\ProductCartItemRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use function sprintf;

#[Entity(repositoryClass: ProductCartItemRepository::class)]
class ProductCartItem extends AbstractCartItem
{
    #[ManyToOne(targetEntity: Product::class, fetch: 'EAGER')]
    #[JoinColumn(referencedColumnName: 'product_id')]
    protected CartInsertableInterface $referencedEntity;

    public function __construct(CartInsertableInterface $referencedEntity, int $quantity)
    {
        parent::__construct($referencedEntity, $quantity);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->getReferenceEntity()
                ->getName(),
            'category' => $this->getReferenceEntity()
                ->getCategory()
                ->getName(),
            'type' => $this->getTypeName(),
            'quantity' => $this->getQuantity(),
        ];
    }

    public function getDisplayName(): string
    {
        return sprintf(
            '%s (%s)',
            $this->getReferencedEntity()
                ->getName(),
            $this->getReferencedEntity()
                ->getCategory()
                ->getName(),
        );
    }
}
