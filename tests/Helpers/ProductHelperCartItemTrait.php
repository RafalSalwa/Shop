<?php

namespace App\Tests\Helpers;

use App\Entity\Product;
use App\Entity\ProductCartItem;
use ReflectionClass;

trait ProductHelperCartItemTrait {

    public function getHelperProductCartItem(int $id = 1): ProductCartItem
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $product = new Product();
        $this->setProtectedProperty($product, 'id', $id);
        $this->setProtectedProperty($product, 'name', 'Product '. $id);
        $this->setProtectedProperty($product, 'quantityPerUnit', 1);
        $this->setProtectedProperty($product, 'name', 'unitsInStock '. 10);
        $this->setProtectedProperty($product, 'price', 100);

        $productCartItem = new ProductCartItem($product,1);
        $this->setProtectedProperty($productCartItem, 'id', $id);
        return $productCartItem;
    }

    private function setProtectedProperty($object, $property, $value)
    {
        $reflection = new ReflectionClass($object);
        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($object, $value);
    }
}
