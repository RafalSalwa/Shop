<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\PaymentProvider;
use App\Repository\PaymentRepository;
use DateTime;
use DateTimeInterface;
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
#[Table(name: 'payment', schema: 'interview')]
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
    private readonly int $id;

    #[Column(name: 'operation_number', type: Types::STRING, length: 40, nullable: false)]
    private string $operationNumber;

    #[Column(name: 'operation_type', type: Types::STRING, length: 40)]
    private string $operationType;

    #[Column(name: 'amount', type: Types::INTEGER, nullable: false)]
    private int $amount;

    #[Column(name: 'status', type: Types::STRING, length: 25)]
    private string $status = self::PENDING;

    #[Column(name: 'payment_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $paymentDate = null;

    #[Column(
        name: 'created_at',
        type: Types::DATETIME_MUTABLE,
        options: ['default' => 'CURRENT_TIMESTAMP'],
    )]
    private DateTime $createdAt;

    #[Column(name: 'user_id', type: Types::INTEGER)]
    private int $userId;

    #[ManyToOne(targetEntity: Order::class, inversedBy: 'payments')]
    #[JoinColumn(name: 'order_id', referencedColumnName: 'order_id', nullable: true)]
    private ?Order $order = null;

    public function __construct(int $userId, int $amount, PaymentProvider $operationType, string $operationNumber)
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->operationType = $operationType->value;
        $this->operationNumber = $operationNumber;

        $this->createdAt = new DateTime('now');
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOperationNumber(): ?string
    {
        return $this->operationNumber;
    }

    public function getOperationType(): PaymentProvider
    {
        return PaymentProvider::from($this->operationType);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getPaymentDate(): ?DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(DateTime $paymentDate): void
    {
        $this->paymentDate = $paymentDate;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }
}
