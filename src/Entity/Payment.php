<?php

declare(strict_types=1);

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
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: PaymentRepository::class)]
#[Table(name: 'payment', schema: "interview")]
class Payment
{
    final public const PENDING = 'pending';
    final public const PROCESSING = 'processing';
    final public const COMPLETED = 'completed';
    final public const CANCELLED = 'cancelled';

    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'payment_id', type: Types::INTEGER, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'payment_paymentID_seq', allocationSize: 1, initialValue: 1)]
    private int $id;
    #[Column(name: 'operation_number', type: Types::STRING, length: 40)]
    private ?string $operationNumber;
    #[Column(name: 'operation_type', type: Types::STRING, length: 40)]
    private string $operationType = 'payment';
    #[Column(name: 'amount', type: Types::INTEGER, nullable: false)]
    private int $amount;
    #[Column(name: 'status', type: Types::STRING, length: 25)]
    private string $status;
    #[Column(name: 'payment_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime $paymentDate;
    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'payments')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'user_id', nullable: true)]
    private ?User $user = null;

    #[ManyToOne(targetEntity: Order::class, inversedBy: 'payments')]
    #[JoinColumn(name: 'order_id', referencedColumnName: 'order_id', nullable: true)]
    private ?Order $order = null;

    public function __construct()
    {
        $this->operationNumber = Uuid::v4()->toBinary();
        $this->createdAt = new DateTime('now');
    }

    public function getAmount($formatted = true): int|string
    {
        return $formatted ? number_format($this->amount / 100, 2, ',', '') : $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getOperationNumber(): ?string
    {
        return $this->operationNumber;
    }

    public function setOperationNumber(?string $operationNumber): self
    {
        $this->operationNumber = $operationNumber;

        return $this;
    }

    public function getOperationType(): ?string
    {
        return $this->operationType;
    }

    public function setOperationType(?string $operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentDate(): DateTime
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(DateTime $paymentDate): self
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }
}
