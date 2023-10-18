<?php

declare(strict_types=1);

namespace App\Entity;

interface CartItemInterface
{
    /**
     * Get identifier of the cart item.
     */
    public function getId(): int;

    /**
     * Get identifier of the base entity that is added.
     *
     * @return int
     */
    public function getReferencedEntity(): CartInsertableInterface;

    /**
     * Get the name or description of the cart item.
     */
    public function getName(): string;

    /**
     * Get the type of the cart item (e.g., "product" or "subscription").
     */
    public function getType(): string;

    /**
     * Get the type name of the cart item (e.g., "product" or "subscription").
     */
    public function getTypeName(): string;

    /**
     * Get the human-readable type name for frontend.
     */
    public function getDisplayName(): string;

    /**
     * Get the price of one unit of the cart item.
     */
    public function getPrice(): float;

    /**
     * Get the quantity of the cart item.
     */
    public function getQuantity(): int;

    /**
     * Get identifier of the base entity that is added.
     */
    public function setReferencedEntity(CartInsertableInterface $entity): self;

    /**
     * Set the quantity of the cart item.
     */
    public function setQuantity(int $quantity): self;

    /**
     * Calculate the total price for the cart item based on quantity.
     */
    public function getTotalPrice(): float;
}
