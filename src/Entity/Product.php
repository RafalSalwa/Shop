<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Contracts\CartInsertableInterface;
use App\Entity\Contracts\StockManageableInterface;
use App\Enum\SubscriptionTier;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;

use function bcdiv;
use function bcmul;
use function sprintf;

/** @psalm-suppress PropertyNotSetInConstructor */
#[Entity(repositoryClass: ProductRepository::class)]
#[Table(name: 'products', schema: 'interview')]
class Product implements CartInsertableInterface, StockManageableInterface
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'product_id', type: Types::INTEGER, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'products_ProductID_seq', allocationSize: 1, initialValue: 80)]
    private int $id;

    #[Column(name: 'product_name', type: Types::STRING, length: 40, nullable: false)]
    private string $name;

    #[Column(name: 'quantity_per_unit', type: Types::STRING, length: 20, nullable: false)]
    private string $quantityPerUnit;

    #[Column(name: 'unit_price', type: Types::SMALLINT, nullable: false)]
    private int $price;

    #[Column(name: 'units_in_stock', type: Types::INTEGER, nullable: false)]
    private int $unitsInStock;

    #[Column(name: 'units_on_order', type: Types::SMALLINT, nullable: false)]
    private int $unitsOnOrder;

    #[ManyToOne(targetEntity: SubscriptionPlan::class)]
    #[JoinColumn(referencedColumnName: 'plan_id', nullable: false)]
    private SubscriptionPlan $subscriptionPlan;

    public function __construct(string $name, string $quantityPerUnit, int $price, int $unitsInStock, int $unitsOnOrder)
    {
        $this->name = $name;
        $this->quantityPerUnit = $quantityPerUnit;
        $this->price = $price;
        $this->unitsInStock = $unitsInStock;
        $this->unitsOnOrder = $unitsOnOrder;
        $this->subscriptionPlan = new SubscriptionPlan('freemium', 'freemium', SubscriptionTier::Freemium, true, true);
    }

    public function getRequiredSubscription(): SubscriptionPlan
    {
        return $this->subscriptionPlan;
    }

    public function getGrossPrice(): string
    {
        $taxedPrice = bcmul((string)$this->getPrice(), '1.23');

        return bcdiv($taxedPrice, '100', 2);
    }

    public function getPrice(): int
    {
        return $this->price;
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

    public function setUnitsInStock(int $unitsInStock): void
    {
        $this->unitsInStock = $unitsInStock;
    }

    public function getUnitsOnOrder(): int
    {
        return $this->unitsOnOrder;
    }

    public function setUnitsOnOrder(int $unitsOnOrder): void
    {
        $this->unitsOnOrder = $unitsOnOrder;
    }

    public function decreaseStock(int $quantity): void
    {
        $this->setUnitsInStock($this->getUnitsInStock() - $quantity);
    }

    public function increaseStock(int $quantity): void
    {
        $this->setUnitsInStock($this->getUnitsInStock() + $quantity);
    }

    public function changeStock(int $quantity): void
    {
        $this->setUnitsInStock($quantity);
    }
}
