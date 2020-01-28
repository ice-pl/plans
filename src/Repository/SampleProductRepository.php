<?php

namespace App\Repository;

use App\Entity\SampleProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SampleProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method SampleProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method SampleProduct[]    findAll()
 * @method SampleProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SampleProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SampleProduct::class);
    }

    // /**
    //  * @return SampleProduct[] Returns an array of SampleProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SampleProduct
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
