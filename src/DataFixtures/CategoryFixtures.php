<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $categoriesData = [
            [
                'name' => 'Fruits',
            ],
            [
                'name' => 'Légumes',
            ],
            [
                'name' => 'Viandes',
            ],
            [
                'name' => 'Poissons',
            ],
            [
                'name' => 'Produits laitiers',
            ],
        ];

        foreach ($categoriesData as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
