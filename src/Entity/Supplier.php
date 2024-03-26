<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: SupplierRepository::class)]
#[Table(name: 'suppliers', schema: 'interview')]
class Supplier
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'supplier_id', type: Types::SMALLINT, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'suppliers_supplier_id_seq', allocationSize: 1, initialValue: 10)]
    private int $id;

    #[Column(name: 'company_name', type: Types::STRING, length: 40, nullable: false)]
    private string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
