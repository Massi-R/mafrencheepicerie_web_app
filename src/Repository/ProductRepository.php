<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param int|null $categoryFilter
     * @param float|null $priceFilter
     * @return Product[]
     */
    public function findByFilters(?int $categoryFilter, ?float $priceFilter): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c');

        if ($categoryFilter) {
            $queryBuilder->andWhere('c.id = :categoryFilter')
                ->setParameter('categoryFilter', $categoryFilter);
        }

        if ($priceFilter) {
            $queryBuilder->andWhere('p.price <= :priceFilter')
                ->setParameter('priceFilter', $priceFilter);
        }

        return $queryBuilder->getQuery()->getResult();
    }


    /**
     * @param int|string $id
     * @return Product|null
     */
    public function findOneById(int|string $id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @param int $int
     * @return Product[]
     */
    public function findByIsBest(int $int): array
    {
        return $this->findBy(['isBest' => $int]);
    }
}
//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
