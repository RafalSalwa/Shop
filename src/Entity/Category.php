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
#[Table(name: 'categories', schema: 'interview')]
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
    private string $name;

    #[Column(name: 'description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Column(name: 'slug', type: Types::STRING, length: 32, nullable: false)]
    private string $slug;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): null|int
    {
        return $this->id;
    }

    public function getName(): null|string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): null|string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
