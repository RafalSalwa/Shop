<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: CategoryRepository::class)]
#[Table(name: 'categories', schema: "interview")]
class Category
{
    /**
     * @var ArrayCollection
     */
    public $products;
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'category_id', type: Types::SMALLINT, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'categories_categoryID_seq', allocationSize: 1, initialValue: 10)]
    private ?int $id = null;
    #[Column(name: 'category_name', type: Types::STRING, length: 15, nullable: false)]
    private ?string $name = null;
    #[Column(name: 'description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getName(): string|null
    {
        return $this->name;
    }

    public function getDescription(): string|null
    {
        return $this->description;
    }
}
