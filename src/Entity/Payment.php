<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
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

#[Entity(repositoryClass: PaymentRepository::class)]
#[Table(name: 'payment')]
class Payment
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'payment_id', type: Types::INTEGER, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'payment_paymentID_seq', allocationSize: 1, initialValue: 1)]
    private int $id;
    #[Column(name: 'operation_number', type: Types::STRING, length: 40)]
    private ?string $operationNumber;
    #[Column(name: 'operation_type', type: Types::STRING, length: 40)]
    private ?string $operationType;
    #[Column(name: 'amount', type: Types::SMALLINT, nullable: false)]
    private int $amount;
    #[Column(name: 'payment_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime $paymentDate;
    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'payments')]
    #[JoinColumn(name: "user_id", referencedColumnName: 'user_id', nullable: true)]
    private $user;


}
