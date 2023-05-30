<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscription
 *
 * @ORM\Table(name="subscription", uniqueConstraints={@ORM\UniqueConstraint(name="subscriptions_user_id_uq", columns={"user_id"})}, indexes={@ORM\Index(name="subscription_plan_id_fk", columns={"plan_id"}), @ORM\Index(name="subscriptions_user_idx", columns={"user_id"}), @ORM\Index(name="plan_created_at_index", columns={"purchased_at"})})
 * @ORM\Entity
 */
class Subscription
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="purchased_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $purchasedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ends_at", type="datetime", nullable=true)
     */
    private $endsAt;

    /**
     * @var \Plan
     *
     * @ORM\ManyToOne(targetEntity="Plan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plan_id", referencedColumnName="id")
     * })
     */
    private $plan;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


}
