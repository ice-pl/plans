<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }



    public function findAll(){
        return $this->findBy(array(), array('position' => 'ASC'));
    }



    public function findProjects_byUserId(int $userId){
        $qb = $this->createQueryBuilder('p');
        $qb
            ->innerJoin('p.user','u')
            ->select()
            ->where('u.id = :id' )
            ->setParameter('id', $userId )
            ->orderBy('p.position', 'ASC');

        return $qb->getQuery()->getResult();
    }



    public function findProjectsId_byUserId(int $userId){
        $qb = $this->createQueryBuilder('p');
        $qb
            ->innerJoin('p.user','u')
            ->select('p.id AS project_id')
            ->where('u.id = :id' )
            ->setParameter('id', $userId )
            ->orderBy('p.position', 'ASC');

        return $qb->getQuery()->getResult();
    }




    public function findProjectsName_byOwnerId(int $ownerId){
        $qb = $this->createQueryBuilder('p');
        $qb
            ->select('p.name AS project_name')
            ->addSelect('p.id AS project_id')
            ->addSelect('p.share_questions AS questions_from_userId')
            ->where('p.owner = :ownerId' )
            ->andWhere('p.share_questions IS NOT NULL')
            ->setParameter('ownerId', $ownerId )
            ->orderBy('p.name', 'ASC');

        return $qb->getQuery()->getResult();
    }



    public function findProjectsName_byUserIdInside(int $userId){
        $qb = $this->createQueryBuilder('p');
        $qb

            ->select('p.name AS project_name')
            ->addSelect('p.owner AS owner_Id')
            ->addSelect('p.id AS project_id')
            ->addSelect('p.share_answers AS answers_to_userId')
            ->where('p.share_answers IS NOT NULL')
            ->andWhere(
                $qb->expr()->like('p.share_answers', ':userId'),
            )
            ->setParameter('userId', '%' .'['. $userId . '%' )

            ->orderBy('p.name', 'ASC');

        return $qb->getQuery()->getResult();
    }





    public function findProjectByName(string $query){
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where(
                $qb->expr()->like('p.name', ':query'),
            )
            ->setParameter('query', '%' . $query . '%' )
            ;

        return $qb->getQuery()->getResult();
    }



    // /**
    //  * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
