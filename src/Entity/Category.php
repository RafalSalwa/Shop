<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: CategoryRepository::class)]
#[Table(name: 'categories')]
class Category
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'category_id', type: Types::SMALLINT, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'categories_categoryID_seq', allocationSize: 1, initialValue: 10)]
    private ?int $id = null;
    #[Column(name: 'category_name', type: Types::STRING, length: 15, nullable: false)]
    private ?string $name = null;
    #[Column(name: 'description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;
    #[Column(name: 'picture', type: Types::BLOB, nullable: true)]
    private $picture;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }
}
