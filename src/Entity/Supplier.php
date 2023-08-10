<?php

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
#[Table(name: 'suppliers')]
class Supplier
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'supplier_id', type: Types::SMALLINT, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'suppliers_supplier_id_seq', allocationSize: 1, initialValue: 10)]
    private ?int $id = null;

    #[Column(name: 'company_name', type: Types::STRING, length: 40, nullable: false)]
    private ?string $name = null;

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
}
