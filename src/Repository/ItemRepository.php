<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    // /**
    //  * @return Item[] Returns an array of Item objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findItems_byProductId(int $productId){

        $qb = $this->createQueryBuilder('i');
        $qb
            ->innerJoin('i.product','p')
            ->select()
            ->andWhere('p.id = :productId' )
            ->setParameter('productId', $productId )
            ->orderBy('i.position', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }




    public function findItemDescription_byId(int $id){

        $qb = $this->createQueryBuilder('i');
        $qb
            ->andWhere('i.id = :id' )
            ->setParameter('id', $id )
            ->select('i.description')
        ;

        return $qb->getQuery()->getResult();
    }




    public function findAllItems_byParentId(int $parentId){

        $qb = $this->createQueryBuilder('i');
        $qb
            ->andWhere('i.parent_id = :parent_id' )
            ->setParameter('parent_id', $parentId )
            // ->select('i.description')
            ->select('i.id')
            ->addSelect('i.interval_time')
            ->addSelect('i.delay_time')
        ;

        return $qb->getQuery()->getResult();
    }




}
