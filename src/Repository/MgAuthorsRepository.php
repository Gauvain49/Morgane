<?php

namespace App\Repository;

use App\Entity\MgAuthors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgAuthors|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgAuthors|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgAuthors[]    findAll()
 * @method MgAuthors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgAuthorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgAuthors::class);
    }

    // /**
    //  * @return MgAuthors[] Returns an array of MgAuthors objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MgAuthors
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
