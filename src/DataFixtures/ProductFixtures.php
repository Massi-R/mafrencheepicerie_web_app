<?php
// src/DataFixtures/ProductFixtures.php
namespace App\DataFixtures;

use App\Entity\Product;
use Faker\Factory;
use Faker\Factory\FactoryInterface;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private CategoryRepository $categoryRepository;


    public function __construct(CategoryRepository $categoryRepository, ParameterBagInterface $parameterBag)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = $this->categoryRepository->findAll();

        $faker = Factory::create('fr_FR');

        $productData = [
            [
                'name' => 'Pomme Gala',
                'subtitle' => 'Pommes fraîches de saison',
                'description' => 'Les pommes Gala sont délicieusement sucrées et croustillantes. Parfaites pour une collation saine ou pour accompagner votre dessert préféré.',
                'image' => 'https://leptitjany.fr/1945-large_default/pomme-grany-smith-150-170-france-hve-cat-1.jpg',
            ],
            [
                'name' => 'Brocoli Bio',
                'subtitle' => 'Brocoli frais et biologique',
                'description' => 'Le brocoli bio est riche en nutriments et est cultivé de manière durable. Un excellent choix pour vos plats cuisinés.',
                'image' => 'https://leptitjany.fr/1944-large_default/brocoli-au-kg-esp.jpg',
            ],
            [
                'name' => 'Filet de Boeuf Angus',
                'subtitle' => 'Viande de bœuf Angus de première qualité',
                'description' => 'Notre filet de bœuf Angus est tendre et savoureux. Idéal pour une cuisson lente au four ou pour griller sur le barbecue.',
                'image' => 'beef.jpg',
            ],
            [
                'name' => 'Poire Conférence',
                'subtitle' => 'Poires juteuses et sucrées',
                'description' => 'La poire Conférence est célèbre pour sa chair juteuse et son goût sucré. Idéale pour les collations et les desserts.',
                'image' => 'https://leptitjany.fr/1925-large_default/poire-passe-crassane-fr.jpg',
            ],
            [
                'name' => 'Carottes Bio',
                'subtitle' => 'Carottes fraîches et biologiques',
                'description' => 'Nos carottes biologiques sont cultivées avec soin pour une saveur optimale. Parfaites pour la cuisine quotidienne.',
                'image' => 'https://leptitjany.fr/2191-large_default/carotte-de-l-ain-le-kg-france.jpg',
            ],
            [
                'name' => 'Yaourt Grec Nature',
                'subtitle' => 'Yaourt riche et crémeux',
                'description' => 'Notre yaourt grec nature est épais, crémeux et délicieusement riche en saveur. Parfait pour le petit-déjeuner ou comme collation saine.',
                'image' => 'greek_yogurt.jpg',
            ],
            [
                'name' => 'Brocoli Frais',
                'subtitle' => 'Brocoli cultivé localement',
                'description' => 'Notre brocoli frais est cultivé localement pour garantir la fraîcheur et la saveur. Idéal pour accompagner de nombreux plats.',
                'image' => 'https://leptitjany.fr/1944-large_default/brocoli-au-kg-esp.jpg',
            ],
            [
                'name' => 'Filet de Bœuf Angus',
                'subtitle' => 'Filet de bœuf de qualité supérieure',
                'description' => 'Le filet de bœuf Angus est connu pour sa tendreté exceptionnelle et sa saveur inégalée. Parfait pour une expérience gastronomique.',
                'image' => 'beef.jpg',
            ],
            [
                'name' => 'Saumon Sauvage',
                'subtitle' => 'Saumon pêché de manière durable',
                'description' => 'Notre saumon sauvage est pêché de manière durable, offrant une option saine et délicieuse. Parfait pour les repas légers.',
                'image' => 'salmon.jpg',
            ],
            [
                'name' => 'Mozzarella Di Bufala',
                'subtitle' => 'Mozzarella de bufflonne authentique',
                'description' => 'La mozzarella di bufala est une délicieuse mozzarella fabriquée à partir de lait de bufflonne. Idéale pour les salades et les plats italiens.',
                'image' => 'mozzarella.jpg',
            ],

];

        foreach ($categories as $category) {
            foreach ($productData as $data) {
                $product = new Product();
                $product->setIsBest(false);
                $product->setIllustration($data['image']);

                $product->setName($data['name'] . ' - ' . $category->getName());
                $product->setSubtitle($data['subtitle']);
                $product->setDescription($data['description']);
                $product->setPrice(number_format(rand(100, 999) / 100, 2));
                $product->setCategory($category);

                $manager->persist($product);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
