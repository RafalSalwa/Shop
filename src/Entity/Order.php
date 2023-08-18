<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Workflow\WorkflowInterface;

#[Entity(repositoryClass: OrderRepository::class)]
#[Table(name: 'order')]
class Order
{
    #[Id]
    #[GeneratedValue(strategy: 'SEQUENCE')]
    #[Column(name: 'order_id', type: Types::INTEGER, unique: true, nullable: false)]
    #[SequenceGenerator(sequenceName: 'order_orderID_seq', allocationSize: 1, initialValue: 1)]
    private $id;

    #[Column(name: 'status', type: Types::STRING, length: 25)]
    private $status;

    private $currentState;

    private $workflow;

    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $updatedAt = null;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[JoinColumn(name: "user_id", referencedColumnName: 'user_id', nullable: true)]
    private $user;

    #[OneToMany(mappedBy: 'order', targetEntity: Payment::class, orphanRemoval: true)]
    private $payments;

    public function __construct(WorkflowInterface $orderWorkflow)
    {
        $this->workflow = $orderWorkflow;
    }

    public function transitionTo(string $transition): void
    {
        if ($this->workflow->can($this, $transition)) {
            $this->workflow->apply($this, $transition);
        }
    }
}
