<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Product;
use Jzonta\FakerRestaurant\Provider\en_US\Restaurant as RestaurantProvider;
use Faker\Factory;
use Faker\Generator;
use Cocur\Slugify\Slugify;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        $faker = Factory::create();
        $slugify = new Slugify();

        // Créez une instance du faker spécifique à jzonta/faker-restaurant
        $restaurantFaker = Factory::create();
        $restaurantFaker->addProvider(new RestaurantProvider($restaurantFaker));

        foreach ($categories as $category) {
            for ($i = 0; $i < 5; $i++) {
                $product = new Product();

                // Utilisez les méthodes spécifiques de jzonta/faker-restaurant
                $productName = $restaurantFaker->foodName();
                $product->setName($productName);
                $product->setIllustration($faker->imageUrl());
                $product->setSlug($slugify->slugify($productName));
                $product->setSubtitle($faker->sentence);
                $product->setDescription($faker->paragraph);
                $product->setPrice($faker->randomFloat(2, 1, 100));
                $product->setCategory($category);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
