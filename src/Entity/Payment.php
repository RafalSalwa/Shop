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
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass: PaymentRepository::class)]
#[Table(name: 'payment')]
class Payment
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';

    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'payment_id', type: Types::INTEGER, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'payment_paymentID_seq', allocationSize: 1, initialValue: 1)]
    private int $id;
    #[Column(name: 'operation_number', type: Types::STRING, length: 40)]
    private ?string $operationNumber;
    #[Column(name: 'operation_type', type: Types::STRING, length: 40)]
    private string $operationType = "payment";
    #[Column(name: 'amount', type: Types::INTEGER, nullable: false)]
    private int $amount;
    #[Column(name: 'status', type: Types::STRING, length: 25)]
    private $status;
    #[Column(name: 'payment_date', type: Types::DATETIME_MUTABLE, nullable: true)]
    private DateTime $paymentDate;
    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'payments')]
    #[JoinColumn(name: "user_id", referencedColumnName: 'user_id', nullable: true)]
    private $user;

    #[ManyToOne(targetEntity: Order::class, inversedBy: 'payments')]
    #[JoinColumn(name: "order_id", referencedColumnName: 'order_id', nullable: true)]
    private $order;

    public function __construct()
    {
        $this->operationNumber = Uuid::v4();
        $this->createdAt = new DateTime('now');
    }

    public function getAmount($formatted = true): int|string
    {
        return $formatted ? number_format($this->amount / 100, 2, ",", "") : $this->amount;
    }

    public function setAmount(int $amount): Payment
    {
        $this->amount = $amount;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Payment
    {
        $this->id = $id;
        return $this;
    }

    public function getOperationNumber(): ?string
    {
        return $this->operationNumber;
    }

    public function setOperationNumber(?string $operationNumber): Payment
    {
        $this->operationNumber = $operationNumber;
        return $this;
    }

    public function getOperationType(): ?string
    {
        return $this->operationType;
    }

    public function setOperationType(?string $operationType): Payment
    {
        $this->operationType = $operationType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Payment
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getPaymentDate(): DateTime
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(DateTime $paymentDate): Payment
    {
        $this->paymentDate = $paymentDate;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Payment
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Payment
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     * @return Payment
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

}
