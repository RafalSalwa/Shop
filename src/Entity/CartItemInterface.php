<?php

namespace App\Entity;

interface CartItemInterface
{
    /**
     * Get identifier of the cart item
     * @return int
     */
    public function getId(): int;

    /**
     * Get the name or description of the cart item.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the type of the cart item (e.g., "product" or "subscription").
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get the type name of the cart item (e.g., "product" or "subscription").
     *
     * @return string
     */
    public function getTypeName(): string;

    /**
     * Get the human-readable type name for frontend
     *
     * @return string
     */
    public function getDisplayName(): string;

    /**
     * Get the price of one unit of the cart item.
     *
     * @return float
     */
    public function getPrice(): float;

    /**
     * Get the quantity of the cart item.
     *
     * @return int
     */
    public function getQuantity(): int;

    /**
     * Set the quantity of the cart item.
     *
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void;

    /**
     * Calculate the total price for the cart item based on quantity.
     *
     * @return float
     */
    public function getTotalPrice(): float;
}
