<?php

namespace App\Repository;

use App\Entity\MgCarriersAmountRegions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MgCarriersAmountRegions|null find($id, $lockMode = null, $lockVersion = null)
 * @method MgCarriersAmountRegions|null findOneBy(array $criteria, array $orderBy = null)
 * @method MgCarriersAmountRegions[]    findAll()
 * @method MgCarriersAmountRegions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MgCarriersAmountRegionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MgCarriersAmountRegions::class);
    }

    // /**
    //  * @return MgCarriersAmountRegions[] Returns an array of MgCarriersAmountRegions objects
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
    public function findOneBySomeField($value): ?MgCarriersAmountRegions
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
