<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 *
 */
class CategoryCrudController extends AbstractCrudController
{
    /**
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    /**
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Categories'),
            BooleanField::new('active'),
            DateTimeField::new('updatedAt', 'Mise à jour le')->hideOnForm(),
            DateTimeField::new('createdAt', 'Crée le')->hideOnForm(),
        ];
    }

    /**
     * @param EntityManagerInterface $em
     * @param $entityInstance
     * @return void
     */
    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof Category) {
            return;
        }

        $entityInstance->setCreatedAt(new \DateTimeImmutable());

        parent::persistEntity($em, $entityInstance);
    }

    /**
     * @param EntityManagerInterface $em
     * @param $entityInstance
     * @return void
     */
    public function deleteEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof Category) {
            return;
        }

        foreach ($entityInstance->getProducts() as $product) {
            $em->remove($product);
        }

        parent::deleteEntity($em, $entityInstance);
    }
}
