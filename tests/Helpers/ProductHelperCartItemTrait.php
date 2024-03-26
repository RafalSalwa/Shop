<?php

namespace App\Tests\Helpers;

use App\Entity\CartItem;
use App\Entity\Contracts\CartItemInterface;
use App\Entity\Product;
use App\Entity\ProductCartItem;

trait ProductHelperCartItemTrait {

    use ProtectedPropertyHelper;

    public function getHelperProduct(int $id): Product
    {
        $product = new Product(
            name: 'Product '. $id,
            quantityPerUnit: '10 pcs',
            unitsOnOrder: 100,
            unitsInStock: 10,
            price: 100_00,
        );
        $this->setProtectedProperty($product, 'id', $id);
        $this->setProtectedProperty($product, 'name', 'Product '. $id);
        $this->setProtectedProperty($product, 'quantityPerUnit', 1);
        $this->setProtectedProperty($product, 'name', 'unitsInStock '. 10);
        $this->setProtectedProperty($product, 'price', 100);

        return $product;
    }

    public function getHelperProductCartItem(int $id = 1): CartItemInterface
    {
        $product = $this->getHelperProduct($id);
        $productCartItem = new CartItem($product,1);
        $this->setProtectedProperty($productCartItem, 'id', $id);
        return $productCartItem;
    }

}
