<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product(
            name: 'test',
            quantityPerUnit: '10 pcs',
            price: 10_00,
            unitsInStock: 100,
            unitsOnOrder: 1,
        );
        $manager->persist($product);

        $manager->flush();
    }
}
