<?php

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
#[Table(name: 'products')]
class Product implements CartInsertableInterface
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
    #[Column(name: 'unit_price', type: Types::SMALLINT, nullable: false)]
    private $unitPrice;
    #[Column(name: 'units_in_stock', type: Types::SMALLINT, nullable: true)]
    private $unitsInStock;
    #[Column(name: 'units_on_order', type: Types::SMALLINT, nullable: true)]
    private $unitsOnOrder;

    public function getName()
    {
        return $this->name;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    public function toCartItem(): CartItem
    {
        $cartItem = new CartItem();
        $cartItem->setProdId($this->getId())
            ->setType('product')
            ->setQuantity(1)
            ->setCreatedAt(new DateTime('now'))
            ->setUpdatedAt(new DateTime('now'));

        return $cartItem;
    }

    public function getId()
    {
        return $this->id;
    }
}
