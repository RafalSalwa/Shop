<?php

declare(strict_types=1);

namespace App\Entity\Contracts;

use App\Entity\Cart;

interface CartItemInterface
{
    /**
     * Get identifier of the cart item.
     */
    public function getId(): int;

    /**
     * Get identifier of the base entity that is added.
     */
    public function getReferencedEntity(): CartInsertableInterface;

    /**
     * Get the name or description of the cart item.
     */
    public function getName(): string;

    /**
     * Get the price of one unit of the cart item.
     */
    public function getPrice(): int;

    /**
     * Calculate the total price for the cart item based on quantity.
     */
    public function getTotalPrice(): int;

    /**
     * Get the quantity of the cart item.
     */
    public function getQuantity(): int;

    public function getType(): string;

    /**
     * Get Assigned cart.
     */
    public function getCart(): Cart;

    /**
     * Assign cart for Item.
     */
    public function setCart(Cart $cart): void;

    /**
     * Instead of add/substract qty, we will update property with new value.
     */
    public function updateQuantity(int $quantity): void;

    public function getItemType(): string;
}
