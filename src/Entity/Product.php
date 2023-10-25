<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: ProductRepository::class)]
#[Table(name: 'products', schema: 'interview')]
class Product implements CartInsertableInterface, StockManageableInterface
{
    final public const STOCK_DECREASE = 'decrease';

    final public const STOCK_INCREASE = 'increase';

    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'product_id', type: Types::INTEGER, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'products_ProductID_seq', allocationSize: 1, initialValue: 80)]
    private $id;

    #[Column(name: 'product_name', type: Types::STRING, length: 40)]
    private $name;

    #[Column(name: 'supplier_id', type: Types::SMALLINT, nullable: true)]
    private $supplierId;

    #[Column(name: 'category_id', type: Types::SMALLINT, nullable: true)]
    private $categoryId;

    #[ManyToOne(targetEntity: 'Category', inversedBy: 'products')]
    #[JoinColumn(referencedColumnName: 'category_id', nullable: false)]
    private Category $category;

    #[Column(name: 'quantity_per_unit', type: Types::STRING, length: 20, nullable: true)]
    private string $quantityPerUnit;

    #[Column(name: 'unit_price', type: Types::SMALLINT, nullable: false)]
    private $unitPrice;

    #[Column(name: 'units_in_stock', type: Types::SMALLINT, nullable: true)]
    private int $unitsInStock;

    #[Column(name: 'units_on_order', type: Types::SMALLINT, nullable: true)]
    private int $unitsOnOrder;

    #[ManyToOne(targetEntity: SubscriptionPlan::class)]
    #[JoinColumn(referencedColumnName: 'plan_id', nullable: true)]
    private ?SubscriptionPlan $requiredSubscription = null;

    public function getRequiredSubscription(): ?SubscriptionPlan
    {
        return $this->requiredSubscription;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getUnitPrice($userFriendly = false)
    {
        if ($userFriendly) {
            return $this->unitPrice / 100;
        }

        return $this->unitPrice;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDisplayName(): string
    {
        return sprintf('%s %s', $this->getTypeName(), $this->getName());
    }

    public function getTypeName(): string
    {
        return 'product';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantityPerUnit(): string
    {
        return $this->quantityPerUnit;
    }

    public function getUnitsInStock(): int
    {
        return $this->unitsInStock;
    }

    public function setUnitsInStock(int $unitsInStock): self
    {
        $this->unitsInStock = $unitsInStock;

        return $this;
    }

    public function getUnitsOnOrder(): int
    {
        return $this->unitsOnOrder;
    }

    public function setUnitsOnOrder(int $unitsOnOrder): self
    {
        $this->unitsOnOrder = $unitsOnOrder;

        return $this;
    }

    public function toCartItem(): CartItem
    {
        $cartItem = new ProductCartItem();
        $cartItem
            ->setQuantity(1)
            ->setReferencedEntity($this)
            ->setCreatedAt(new DateTime('now'))
            ->setUpdatedAt(new DateTime('now'));

        return $cartItem;
    }

    public function decreaseStock(StockManageableInterface $product, int $quantity): StockManageableInterface
    {
    }

    public function increaseStock(StockManageableInterface $product, int $quantity): StockManageableInterface
    {
    }

    public function changeStock(StockManageableInterface $product, int $quantity): StockManageableInterface
    {
    }
}
