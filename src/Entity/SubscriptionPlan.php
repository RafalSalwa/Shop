<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\SubscriptionTier;
use App\Repository\PlanRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: PlanRepository::class)]
#[Table(name: 'plan', schema: 'interview')]
#[Index(columns: ['plan_name'], name: 'u_plan_idx')]
#[Cache(usage: 'READ_ONLY')]
#[HasLifecycleCallbacks]
class SubscriptionPlan
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'plan_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    #[Column(name: 'plan_name', type: Types::STRING, length: 255)]
    private string $name;

    #[Column(name: 'description', type: Types::TEXT, nullable: false)]
    #[Assert\NotBlank(message: 'Description cannot be empty')]
    #[Assert\Length(min: 10, minMessage: 'You need to add any')]
    private string $description;

    #[Column(name: 'is_active', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $isActive = false;

    #[Column(name: 'is_visible', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $isVisible = true;

    #[Column(name: 'unit_price', type: Types::SMALLINT, nullable: false)]
    private int $price;

    #[Column(name: 'tier', type: Types::SMALLINT, nullable: false)]
    private int $tier;

    #[Column(
        name: 'created_at',
        type: Types::DATETIME_IMMUTABLE,
        options: ['default' => 'CURRENT_TIMESTAMP'],
    )]
    private DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->tier = SubscriptionTier::Freemium->value();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getDescription(): string|null
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getTier(): int
    {
        return $this->tier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
