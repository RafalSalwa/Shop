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
    public function getReferencedEntity(): CartInsertableInterface|StockManageableInterface;

    /**
     * Get the name or description of the cart item.
     */
    public function getName(): string;

    /**
     * Get the price of one unit of the cart item.
     */
    public function getPrice(): string;

    /**
     * Calculate the total price for the cart item based on quantity.
     */
    public function getTotalPrice(): string;

    /**
     * Get the quantity of the cart item.
     */
    public function getQuantity(): int;

    /**
     * @return string
     * Determines which Entity was used For this item (i.e. Product,subscription)
     */
    public function getItemType(): string;

    /**
     * Assign cart for Item
     */
    public function setCart(Cart $cart): void;

    /**
     * Instead of add/substract qty, we will update property with new value.
     */
    public function updateQuantity(int $quantity): void;
}