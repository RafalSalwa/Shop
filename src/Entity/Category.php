<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;

use function mb_strtolower;
use function str_replace;

#[Entity(repositoryClass: CategoryRepository::class)]
#[Table(name: 'categories', schema: 'interview')]
class Category
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'category_id', type: Types::SMALLINT, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'categories_categoryID_seq', allocationSize: 1, initialValue: 10)]
    private ?int $id = null;

    #[Column(name: 'category_name', type: Types::STRING, length: 32, nullable: false)]
    private string $name;

    #[Column(name: 'description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Column(name: 'slug', type: Types::STRING, length: 64, nullable: false)]
    private string $slug;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->slug = mb_strtolower(str_replace(' ', '-', $name));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
