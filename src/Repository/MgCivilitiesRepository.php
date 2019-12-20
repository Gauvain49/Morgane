<?php

namespace App\Repository;

use App\Entity\MgCivilities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCivilities|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCivilities|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCivilities[]    findAll()
 * @method MgCivilities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCivilitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCivilities::class);
    }

    // /**
    //  * @return MgCivilities[] Returns an array of MgCivilities objects
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
    public function findOneBySomeField($value): ?MgCivilities
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
