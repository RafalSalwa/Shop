<?php

namespace App\Tests\Helpers;

use App\Entity\Product;
use App\Entity\ProductCartItem;

trait ProductHelperCartItemTrait {

    use ProtectedPropertyHelper;

    public function getHelperProduct(int $id): Product
    {
        $product = new Product();
        $this->setProtectedProperty($product, 'id', $id);
        $this->setProtectedProperty($product, 'name', 'Product '. $id);
        $this->setProtectedProperty($product, 'quantityPerUnit', 1);
        $this->setProtectedProperty($product, 'name', 'unitsInStock '. 10);
        $this->setProtectedProperty($product, 'price', 100);

        return $product;
    }

    public function getHelperProductCartItem(int $id = 1): ProductCartItem
    {
        $product = $this->getHelperProduct($id);
        $productCartItem = new ProductCartItem($product,1);
        $this->setProtectedProperty($productCartItem, 'id', $id);
        return $productCartItem;
    }

}
