<?php

namespace App\Repository;

use App\Entity\SampleItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SampleItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SampleItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SampleItem[]    findAll()
 * @method SampleItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SampleItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SampleItem::class);
    }

    // /**
    //  * @return SampleItem[] Returns an array of SampleItem objects
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
    public function findOneBySomeField($value): ?SampleItem
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findSampleItems_bySampleProductId(int $id){

        $qb = $this->createQueryBuilder('i');
        $qb
            ->innerJoin('i.sample_product','p')
            ->select()
            ->andWhere('p.id = :sampleProductId' )
            ->setParameter('sampleProductId', $id )
            // ->orderBy('i.position', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }




    public function findSampleItemDescription_byId(int $id){

        $qb = $this->createQueryBuilder('i');
        $qb
            ->andWhere('i.id = :id' )
            ->setParameter('id', $id )
            ->select('i.description')
        ;

        return $qb->getQuery()->getResult();
    }







}
