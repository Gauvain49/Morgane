<?php

namespace App\Repository;

use App\Entity\MgOrdersStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgOrdersStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgOrdersStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgOrdersStatus[]    findAll()
 * @method MgOrdersStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgOrdersStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgOrdersStatus::class);
    }

    // /**
    //  * @return MgOrdersStatus[] Returns an array of MgOrdersStatus objects
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
    public function findOneBySomeField($value): ?MgOrdersStatus
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
