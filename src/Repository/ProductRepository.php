<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */



    public function findProducts_ByUserId(int $userId){

        $qb = $this->createQueryBuilder('p');
        $qb
            ->innerJoin('p.user','u')
            ->select()
            ->where('u.id = :id' )
            ->setParameter('id', $userId )
            ->orderBy('p.position', 'ASC');

        return $qb->getQuery()->getResult();
    }




    public function findProducts_byProjectId(int $projectId){

        $qb = $this->createQueryBuilder('d');
        $qb
            ->innerJoin('d.project','j')
            ->select()
            ->where('j.id = :id' )
            ->setParameter('id', $projectId )
            ->orderBy('d.position', 'ASC');

        return $qb->getQuery()->getResult();
    }




}
