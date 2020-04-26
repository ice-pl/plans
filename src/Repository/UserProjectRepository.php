<?php

namespace App\Repository;

use App\Entity\UserProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Doctrine\ORM\Query;




/**
 * @method UserProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProject[]    findAll()
 * @method UserProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProject::class);
    }

    // /**
    //  * @return UserProject[] Returns an array of UserProject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserProject
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */




    // public function findProjectId_byUserId(int $id){

    //     $qb = $this->createQueryBuilder('up');
    //     $qb
    //         ->andWhere('up.userMap = :id' )
    //         ->setParameter('id', $id )
    //         ->select('up.project_map_id')
    //         // ->select('i.description')

    //         // ->select()
    //     ;

    //     return $qb->getQuery()->getResult();
    // }












    public function findAll()
    {
        $fields = [
            'u.id AS userId',
            'p.id AS projectId',
        ];
 
        return
            $this
                ->createQueryBuilder('up')
                ->select($fields)
                ->join('up.userMap', 'u')
                ->join('up.projectMap', 'p')
                ->addOrderBy('userId', 'ASC')
                ->addOrderBy('projectId', 'ASC')
                ->getQuery()
                ->getResult(Query::HYDRATE_SCALAR)
            ;
    }



    public function findAll_byUserId(int $id){

        $fields = [
            'u.id AS userId',
            'p.id AS projectId',
        ];
 
        return
            $this
                ->createQueryBuilder('up')
                ->andWhere('u.id = :id' )
                ->setParameter('id', $id )
                ->select($fields)
                ->join('up.userMap', 'u')
                ->join('up.projectMap', 'p')
                ->addOrderBy('userId', 'ASC')
                ->addOrderBy('projectId', 'ASC')
                ->getQuery()
                ->getResult(Query::HYDRATE_SCALAR)
            ;
    }



    public function findAll_byProjectId(int $id){

        $fields = [
            'u.id AS userId',
            'p.id AS projectId',
        ];
 
        return
            $this
                ->createQueryBuilder('up')
                ->andWhere('p.id = :id' )
                ->setParameter('id', $id )
                ->select($fields)
                ->join('up.userMap', 'u')
                ->join('up.projectMap', 'p')
                ->addOrderBy('userId', 'ASC')
                ->addOrderBy('projectId', 'ASC')
                ->getQuery()
                ->getResult(Query::HYDRATE_SCALAR)
            ;
    }



    public function findProjectId_byUserId(int $id){

        // $fields = [
        //     'u.id AS userId',
        //     'p.id AS projectId',
        // ];
 
        return
            $this
                ->createQueryBuilder('up')
                ->andWhere('u.id = :id' )
                ->setParameter('id', $id )
                ->select('p.id AS projectId')
                ->join('up.userMap', 'u')
                ->join('up.projectMap', 'p')
                // ->addOrderBy('projectId', 'ASC')
                ->orderBy('p.position', 'ASC')

                ->getQuery()
                ->getResult(Query::HYDRATE_SCALAR)
            ;
    }


    public function findAll_byUserId_byProjectId(int $user_Id, int $project_Id){

        $fields = [
            'u.id AS userId',
            'p.id AS projectId',
            'up.id AS user_project_Id',
        ];
 
        return
            $this
                ->createQueryBuilder('up')
                ->andWhere('u.id = :user_Id' )
                ->setParameter('user_Id', $user_Id )

                ->andWhere('p.id = :project_Id' )
                ->setParameter('project_Id', $project_Id )


                ->select($fields)
                ->join('up.userMap', 'u')
                ->join('up.projectMap', 'p')
                ->addOrderBy('userId', 'ASC')
                ->addOrderBy('projectId', 'ASC')
                ->getQuery()
                ->getResult(Query::HYDRATE_SCALAR)
            ;
    }



    public function findUserProjectId_byUserId_byProjectId(int $user_Id, int $project_Id){

        // $fields = [
        //     'u.id AS userId',
        //     'p.id AS projectId',
        //     'up.id AS user_project_Id',
        // ];
 
        return
            $this
                ->createQueryBuilder('up')
                ->andWhere('u.id = :user_Id' )
                ->setParameter('user_Id', $user_Id )

                ->andWhere('p.id = :project_Id' )
                ->setParameter('project_Id', $project_Id )

                ->select('up.id AS user_project_Id')

                ->join('up.userMap', 'u')
                ->join('up.projectMap', 'p')
                ->getQuery()
                ->getResult(Query::HYDRATE_SCALAR)
            ;
    }

}
