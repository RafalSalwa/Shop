<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\SubscriptionTier;
use App\Repository\SubscriptionRepository;
use DateInterval;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: SubscriptionRepository::class)]
#[Table(name: 'subscription', schema: 'interview')]
#[HasLifecycleCallbacks]
class Subscription
{
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    #[Column(name: 'subscription_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    #[ManyToOne(targetEntity: 'SubscriptionPlan')]
    #[JoinColumn(name: 'subscription_plan_id', referencedColumnName: 'plan_id', nullable: true)]
    private ?SubscriptionPlan $plan = null;

    #[Column(name: 'user_id', type: Types::INTEGER, nullable: true)]
    private ?int $userId = null;

    #[Column(name: 'tier', type: Types::SMALLINT, enumType: SubscriptionTier::class, nullable: false)]
    private SubscriptionTier $tier;

    #[Column(name: 'is_active', type: Types::BOOLEAN, options: [
        'default' => true,
    ])]
    private bool $isActive = true;

    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: [
        'default' => 'CURRENT_TIMESTAMP',
    ])]
    private DateTime $createdAt;

    #[Column(name: 'starts_at', type: Types::DATETIME_MUTABLE, nullable: true, options: [
        'default' => 'CURRENT_TIMESTAMP',
    ])]
    private ?DateTime $startsAt = null;

    #[Column(name: 'ends_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $endsAt = null;

    public function __construct()
    {
        $this->tier = SubscriptionTier::Freemium;
        $date = new DateTime();
        $date->add(new DateInterval('P30D'));
        $this->endsAt = $date;
    }

    public function getPlan(): ?SubscriptionPlan
    {
        return $this->plan;
    }

    public function setPlan(SubscriptionPlan $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function setTier(int $tier): self
    {
        $this->tier = $tier;

        return $this;
    }

    public function getTier(): SubscriptionTier
    {
        return $this->tier;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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

    public function getStartsAt(): ?DateTime
    {
        return $this->startsAt;
    }

    public function setStartsAt(?DateTime $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?DateTime
    {
        return $this->endsAt;
    }

    public function setEndsAt(?DateTime $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getRequiredLevel(): int
    {
        return $this->getTier()->value;
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

    #[PrePersist]
    public function prePersist(): void
    {
        $this->setCreatedAt(new DateTime('now'));
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
