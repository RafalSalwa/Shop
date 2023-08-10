<?php

namespace App\Entity;

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

#[Entity(repositoryClass: ProductRepository::class)]
#[Table(name: 'products')]
class Product
{
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
    private $quantityPerUnit;
    #[Column(name: 'unit_price', type: Types::FLOAT, nullable: true)]
    private $unitPrice;
    #[Column(name: 'units_in_stock', type: Types::SMALLINT, nullable: true)]
    private $unitsInStock;
    #[Column(name: 'units_on_order', type: Types::SMALLINT, nullable: true)]
    private $unitsOnOrder;
    #[Column(name: 'reorder_level', type: Types::SMALLINT, nullable: true)]
    private $reorderLevel;
    #[Column(name: 'discontinued', type: Types::INTEGER, nullable: false)]
    private $discontinued;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSupplierId()
    {
        return $this->supplierId;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getQuantityPerUnit()
    {
        return $this->quantityPerUnit;
    }

    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    public function getUnitsInStock()
    {
        return $this->unitsInStock;
    }

    public function getUnitsOnOrder()
    {
        return $this->unitsOnOrder;
    }

    public function getReorderLevel()
    {
        return $this->reorderLevel;
    }

    public function getDiscontinued()
    {
        return $this->discontinued;
    }
}
