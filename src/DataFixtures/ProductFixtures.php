<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Product;
use Faker\Factory;
use Cocur\Slugify\Slugify;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Récupérer les catégories existantes depuis la base de données
        $categories = $manager->getRepository(Category::class)->findAll();
        $faker = Factory::create();
        $slugify = new Slugify();

        foreach ($categories as $category) {
            for ($i = 0; $i < 2; $i++) {
                $product = new Product();
                $product->setName($faker->unique()->word);
                $product->setIllustration($faker->imageUrl());
                // Utilisez la fonction de slugification si disponible
                // $product->setSlug($slugify->slugify($product->getName()));
                $product->setSubtitle($faker->sentence);
                $product->setDescription($faker->paragraph);
                // Utilisez mt_rand pour générer des nombres entiers aléatoires
                $product->setPrice(mt_rand(100, 10000) / 100); // Prix entre 1 et 100
                $product->setCategory($category);


                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
