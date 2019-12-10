<?php

namespace App\Repository;

use App\Entity\MgLanguages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgLanguages|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgLanguages|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgLanguages[]    findAll()
 * @method MgLanguages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgLanguagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgLanguages::class);
    }

    // /**
    //  * @return MgLanguages[] Returns an array of MgLanguages objects
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
    public function findOneBySomeField($value): ?MgLanguages
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
