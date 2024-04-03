<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Entity\Cart;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Product;
use App\Entity\ProductCartItem;

use function sprintf;

trait ProductHelperCartItemTrait
{
    use ProtectedPropertyTrait;

    public function getHelperProduct(int $id): Product
    {
        $name = sprintf('Product %s', $id);
        $product = new Product(
            name: $name,
            quantityPerUnit: '10 pcs',
            price: 100_00,
            unitsInStock: 10,
            unitsOnOrder: 100,
        );
        $this->setProtectedProperty($product, 'id', $id);
        $this->setProtectedProperty($product, 'name', $name);
        $this->setProtectedProperty($product, 'quantityPerUnit', 1);
        $this->setProtectedProperty($product, 'unitsInStock', 10);
        $this->setProtectedProperty($product, 'price', 100);

        return $product;
    }

    public function getHelperProductCartItem(int $id = 1): CartItemInterface
    {
        $product = $this->getHelperProduct($id);
        $cart = new Cart(userId: $id);
        $productCartItem = new ProductCartItem($cart, $product, 1);
        $this->setProtectedProperty($productCartItem, 'id', $id);

        return $productCartItem;
    }
}
