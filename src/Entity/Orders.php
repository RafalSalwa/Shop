<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders", uniqueConstraints={@ORM\UniqueConstraint(name="subscriptions_user_id_uq", columns={"user_id"})}, indexes={@ORM\Index(name="orders_voucher_id_fk", columns={"voucher_id"}), @ORM\Index(name="orders_subscriptions_id_fk", columns={"subscription_id"})})
 * @ORM\Entity
 */
class Orders
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
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Subscription
     *
     * @ORM\ManyToOne(targetEntity="Subscription")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscription_id", referencedColumnName="id")
     * })
     */
    private $subscription;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Voucher
     *
     * @ORM\ManyToOne(targetEntity="Voucher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="voucher_id", referencedColumnName="id")
     * })
     */
    private $voucher;


}
