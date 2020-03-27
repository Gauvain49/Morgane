<?php

namespace App\Repository;

use App\Entity\MgCarriersStepsRegions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCarriersStepsRegions|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCarriersStepsRegions|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCarriersStepsRegions[]    findAll()
 * @method MgCarriersStepsRegions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCarriersStepsRegionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCarriersStepsRegions::class);
    }

    // /**
    //  * @return MgCarriersStepsRegions[] Returns an array of MgCarriersStepsRegions objects
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
    public function findOneBySomeField($value): ?MgCarriersStepsRegions
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
