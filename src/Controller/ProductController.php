<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
private EntityManagerInterface $entityManager;

public function __construct(EntityManagerInterface $entityManager)
{
$this->entityManager = $entityManager;
}

#[Route('/nos-produits', name: 'app_products')]
public function index(Request $request): Response
{
$categories = $this->entityManager->getRepository(Category::class)->findAll();
$selectedCategory = $request->query->get('category');

if ($selectedCategory) {
$products = $this->entityManager->getRepository(Product::class)->findByCategory($selectedCategory);
} else {
$products = $this->entityManager->getRepository(Product::class)->findAll();
}

return $this->render('product/index.html.twig', [
'products' => $products,
'categories' => $categories,
'selectedCategory' => $selectedCategory,
]);
}
}
