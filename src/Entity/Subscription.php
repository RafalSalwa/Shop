<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
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
#[Table(name: 'subscription')]
#[HasLifecycleCallbacks]
class Subscription
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'subscription_id', type: Types::INTEGER, unique: true, nullable: false)]
    private int $id;

    #[ManyToOne(targetEntity: 'SubscriptionPlan')]
    #[JoinColumn(name: "subscription_plan_id", referencedColumnName: 'plan_id', nullable: true)]
    private $subscriptionPlan;

    #[Column(name: 'tier', type: Types::SMALLINT, nullable: true)]
    private int $tier;
    #[Column(name: 'is_active', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $isActive = false;

    #[Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[Column(name: 'starts_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $startsAt = null;

    #[Column(name: 'ends_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $endsAt = null;

    /**
     * @return mixed
     */
    public function getSubscriptionPlan()
    {
        return $this->subscriptionPlan;
    }

    /**
     * @param mixed $subscriptionPlan
     * @return Subscription
     */
    public function setSubscriptionPlan($subscriptionPlan)
    {
        $this->subscriptionPlan = $subscriptionPlan;
        return $this;
    }

    public function isTier(): bool
    {
        return $this->tier;
    }

    public function setTier(bool $tier): Subscription
    {
        $this->tier = $tier;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): Subscription
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): Subscription
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getStartsAt(): ?DateTime
    {
        return $this->startsAt;
    }

    public function setStartsAt(?DateTime $startsAt): Subscription
    {
        $this->startsAt = $startsAt;
        return $this;
    }

    public function getEndsAt(): ?DateTime
    {
        return $this->endsAt;
    }

    public function setEndsAt(?DateTime $endsAt): Subscription
    {
        $this->endsAt = $endsAt;
        return $this;
    }

    public function getRequiredLevel()
    {
        return $this->getId();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Subscription
    {
        $this->id = $id;
        return $this;
    }

    #[PrePersist]
    public function prePersist(): void
    {
        $this->setCreatedAt(new DateTime('now'));
    }

}
