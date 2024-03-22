<?php

namespace App\Tests\Helpers;

use App\Entity\Product;
use App\Entity\ProductCartItem;

trait ProductHelperCartItemTrait {

    public function getHelperProductCartItem(): ProductCartItem
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productMock->id = 1;
        $productMock->name = 'Product 1';
        $productMock->quantityPerUnit = 1;
        $productMock->unitsInStock = 10;
        $productMock->price = 100;

        return new ProductCartItem($productMock, 1);
    }
}
