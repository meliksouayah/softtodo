<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
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

    public function add(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchAccueil($name,$status,$filename)
    {

        $qb = $this->createQueryBuilder("u");


        $qb->select('u');




        if($name !=''){
            $qb
                ->andWhere("u.title LIKE :q")
                ->setParameter('q','%'.$name.'%');


        }
        if($status !=''){
            $qb
                ->andWhere("u.status LIKE :q")
                ->setParameter('q','%'.$status.'%');


        }
        if($filename !=''){
            $qb
                ->andWhere("u.filename LIKE :q")
                ->setParameter('q','%'.$filename.'%');


        }





        return $qb ->getQuery();

    }

    public function all()
    {

        return $this->createQueryBuilder("u")
            ->orderBy('u.createdat','DESC')

            ->getQuery();
    }
    public function afiiche_all()
    {

        return $this->createQueryBuilder("u")



            ->orderBy('u.createdat','DESC')





            ->getQuery()->getResult();
    }

//    /**
//     * @return News[] Returns an array of News objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?News
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
